<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Invoice;
use App\Models\QuickbooksConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class QbwcSoapController extends Controller
{
    public function handle(Request $request)
    {
        $xmlContent = $request->getContent();
        
        // Log SOAP Request
        AuditLog::create([
            'direction' => 'inbound_qb',
            'type' => 'soap_request',
            'payload' => $xmlContent,
        ]);

        // Parse XML using SimpleXML
        // Clean namespaces for simpler parsing
        $cleanXml = preg_replace('/(<\/?[a-zA-Z0-9\-]+):([a-zA-Z0-9\-]+)/', '$1_$2', $xmlContent);
        $xml = @simplexml_load_string($cleanXml);

        if (!$xml) {
            return $this->soapFault("Invalid XML structure");
        }

        // Find the method being called in the Body
        $body = $xml->soap_Body ?? $xml->Body ?? null;
        if (!$body) {
            return $this->soapFault("SOAP Body not found");
        }

        $methodNode = null;
        $methodName = '';
        foreach ($body->children() as $child) {
            $methodName = $child->getName();
            // strip namespace prefixes if any
            $methodName = preg_replace('/^.*_/', '', $methodName);
            $methodNode = $child;
            break;
        }

        if (!$methodNode) {
            return $this->soapFault("SOAP Method not found");
        }

        // Route method
        $responseXml = '';
        switch ($methodName) {
            case 'serverVersion':
                $responseXml = $this->handleServerVersion($methodNode);
                break;
            case 'clientVersion':
                $responseXml = $this->handleClientVersion($methodNode);
                break;
            case 'authenticate':
                $responseXml = $this->handleAuthenticate($methodNode);
                break;
            case 'sendRequestXML':
                $responseXml = $this->handleSendRequestXML($methodNode);
                break;
            case 'receiveResponseXML':
                $responseXml = $this->handleReceiveResponseXML($methodNode);
                break;
            case 'connectionError':
                $responseXml = $this->handleConnectionError($methodNode);
                break;
            case 'getLastError':
                $responseXml = $this->handleGetLastError($methodNode);
                break;
            case 'closeConnection':
                $responseXml = $this->handleCloseConnection($methodNode);
                break;
            default:
                return $this->soapFault("Unsupported SOAP method: $methodName");
        }

        // Log SOAP Response
        AuditLog::create([
            'direction' => 'outbound_qb',
            'type' => 'soap_response',
            'payload' => $responseXml,
        ]);

        return response($responseXml, 200)
            ->header('Content-Type', 'text/xml; charset=utf-8');
    }

    protected function handleServerVersion($node): string
    {
        return $this->wrapResponse('serverVersionResponse', 'serverVersionResult', '1.0.0');
    }

    protected function handleClientVersion($node): string
    {
        return $this->wrapResponse('clientVersionResponse', 'clientVersionResult', '');
    }

    protected function handleAuthenticate($node): string
    {
        $username = (string)$node->strUserName;
        $password = (string)$node->strPassword;

        $conn = QuickbooksConnection::where('username', $username)->first();

        // Simple auth check (in demo, allow if password matches or connection exists)
        if ($conn && Hash::check($password, $conn->password_hash)) {
            $ticket = Str::uuid()->toString();
            $conn->update([
                'ticket' => $ticket,
                'sync_status' => 'syncing',
                'last_sync_at' => now(),
            ]);

            // Return ticket and empty company file (meaning use currently open file)
            return $this->wrapResponseArray('authenticateResponse', 'authenticateResult', [
                $ticket,
                '' // empty string means use currently open file
            ]);
        }

        return $this->wrapResponseArray('authenticateResponse', 'authenticateResult', [
            'invalid',
            'none'
        ]);
    }

    protected function handleSendRequestXML($node): string
    {
        $ticket = (string)$node->ticket;
        $conn = QuickbooksConnection::where('ticket', $ticket)->first();

        if (!$conn) {
            return $this->wrapResponse('sendRequestXMLResponse', 'sendRequestXMLResult', '');
        }

        // Fetch any pending invoices to sync for this tenant
        $pendingInvoice = Invoice::where('tenant_id', $conn->tenant_id)
            ->where('status', 'pending')
            ->first();

        if (!$pendingInvoice) {
            // No specific pending invoice. Request the last 10 invoices from QuickBooks
            // so we can capture their raw XML payload structure in our audit logs.
            $qbxml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="13.0"?>
<QBXML>
  <QBXMLMsgsRq onError="continueOnError">
    <InvoiceQueryRq requestID="debug_all_invoices">
      <MaxReturned>10</MaxReturned>
      <IncludeLineItems>true</IncludeLineItems>
    </InvoiceQueryRq>
  </QBXMLMsgsRq>
</QBXML>';

            return $this->wrapResponse('sendRequestXMLResponse', 'sendRequestXMLResult', $qbxml);
        }

        // Generate qbXML query request to query QuickBooks for the details of this invoice
        // Or if we already have it, we can query details or update.
        // Let's query the specific Invoice TxnID to verify details or send a query
        $qbxml = '<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="13.0"?>
<QBXML>
  <QBXMLMsgsRq onError="stopOnError">
    <InvoiceQueryRq requestID="1">
      <TxnID>' . htmlspecialchars($pendingInvoice->qb_txn_id) . '</TxnID>
      <IncludeLineItems>true</IncludeLineItems>
    </InvoiceQueryRq>
  </QBXMLMsgsRq>
</QBXML>';

        return $this->wrapResponse('sendRequestXMLResponse', 'sendRequestXMLResult', $qbxml);
    }

    protected function handleReceiveResponseXML($node): string
    {
        $ticket = (string)$node->ticket;
        $responseXml = (string)$node->response;
        $hresult = (string)$node->hresult;
        $message = (string)$node->message;

        $conn = QuickbooksConnection::where('ticket', $ticket)->first();

        if ($conn) {
            // Process the received qbXML response from QuickBooks Desktop
            // Parse XML response
            $cleanXml = preg_replace('/(<\/?[a-zA-Z0-9\-]+):([a-zA-Z0-9\-]+)/', '$1_$2', $responseXml);
            $xml = @simplexml_load_string($cleanXml);
            
            if ($xml) {
                // Find InvoiceRet
                $invoiceRet = $xml->xpath('//InvoiceRet');
                if (!empty($invoiceRet)) {
                    foreach ($invoiceRet as $inv) {
                        $txnId = (string)$inv->TxnID;
                        $editSeq = (string)$inv->EditSequence;
                        $refNumber = (string)$inv->RefNumber;
                        $customerName = (string)$inv->CustomerRef->FullName;
                        $amount = (float)$inv->Subtotal;
                        $tax = (float)$inv->SalesTaxTotal;
                        $total = (float)$inv->AppliedAmount;

                        $txnDate = (string)$inv->TxnDate;

                        $invoice = Invoice::updateOrCreate(
                            [
                                'tenant_id' => $conn->tenant_id,
                                'qb_txn_id' => $txnId,
                            ],
                            [
                                'qb_edit_sequence' => $editSeq,
                                'qb_invoice_number' => $refNumber ?: 'INV-' . $txnId,
                                'customer_name' => $customerName,
                                'transaction_date' => $txnDate ?: now()->format('Y-m-d'),
                                'amount_before_tax' => $amount,
                                'tax_amount' => $tax,
                                'total_amount' => $total ?: $amount + $tax,
                                'status' => 'pending',
                            ]
                        );

                        if ($invoice->wasRecentlyCreated || $invoice->status === 'pending') {
                            // Dispatch asynchronous background job to submit this invoice to MRA EIS API
                            \App\Jobs\SubmitInvoiceToMra::dispatch($invoice->id);
                        }
                    }
                }
            }
        }

        // Return 100% complete
        return $this->wrapResponse('receiveResponseXMLResponse', 'receiveResponseXMLResult', 100);
    }

    protected function handleConnectionError($node): string
    {
        return $this->wrapResponse('connectionErrorResponse', 'connectionErrorResult', 'done');
    }

    protected function handleGetLastError($node): string
    {
        return $this->wrapResponse('getLastErrorResponse', 'getLastErrorResult', 'No error');
    }

    protected function handleCloseConnection($node): string
    {
        $ticket = (string)$node->ticket;
        $conn = QuickbooksConnection::where('ticket', $ticket)->first();

        if ($conn) {
            $conn->update([
                'ticket' => null,
                'sync_status' => 'idle',
            ]);
        }

        return $this->wrapResponse('closeConnectionResponse', 'closeConnectionResult', 'OK');
    }

    protected function wrapResponse(string $responseName, string $resultName, $value): string
    {
        return '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <soap:Body>
    <' . $responseName . ' xmlns="http://developer.intuit.com/">
      <' . $resultName . '>' . htmlspecialchars($value) . '</' . $resultName . '>
    </' . $responseName . '>
  </soap:Body>
</soap:Envelope>';
    }

    protected function wrapResponseArray(string $responseName, string $resultName, array $values): string
    {
        $strings = '';
        foreach ($values as $val) {
            $strings .= '<string>' . htmlspecialchars($val) . '</string>';
        }

        return '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <soap:Body>
    <' . $responseName . ' xmlns="http://developer.intuit.com/">
      <' . $resultName . '>
        ' . $strings . '
      </' . $resultName . '>
    </' . $responseName . '>
  </soap:Body>
</soap:Envelope>';
    }

    protected function soapFault(string $message): string
    {
        return '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <soap:Fault>
      <faultcode>soap:Client</faultcode>
      <faultstring>' . htmlspecialchars($message) . '</faultstring>
    </soap:Fault>
  </soap:Body>
</soap:Envelope>';
    }
}
