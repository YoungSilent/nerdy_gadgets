<?php

function create_bank_select($label = 'Selecteer bank', $current = '') {
    $html = '
        <label for="bankselect">'.$label.'</label>
        <select id="bankselect" name="issuerid" class="form-control">
            <option value="">...</option>';
    if (IDEALMODE == 'mollie') {
        $mollie = new Mollie_API_Client;
        $mollie->setApiKey(MOLLIE_API_KEY);
        $issuers = $mollie->issuers->all();
        if (!$issuers) return 'Kan de bankenlijst niet ophalen.';
        foreach ($issuers as $issuer) {
            if ($issuer->method == Mollie_API_Object_Method::IDEAL) {
                $html .= '
            <option value="'.$issuer->id.'"';
                if ($issuer->id == $current) $html .= ' selected="selected"';
                $html .= '>'.$issuer->name.'</option>';
            }
        }
    } else {
        $sisow = new Sisow(SISOW_MERCHANT_ID, SISOW_MERCHANT_KEY);
        $test = (SISOW_TEST == 'true') ? true : false;
        $sisow->DirectoryRequest($selectdata, false, $test);
        if (!$selectdata) return 'Kan de bankenlijst niet ophalen.';
        foreach ($selectdata as $key => $val) {
            $html .= '
            <option value="'.$key.'"';
            if ($current == $key) $html .= ' selected="selected"';
            $html .= '>'.$val.'</option>';
        }
    }
    $html .= '
        </select>';
    return $html;
}

function create_ideal_payment($obj) {
    $msg = '';
    $cssclass = '';
    $redirect_url = '';
    $trans_id = '';
    $factuur_id = sprintf('%d%04d', date('Y'), $obj->factuur_id);
    if (IDEALMODE == 'mollie') {
        try {
            $mollie = new Mollie_API_Client;
            $mollie->setApiKey(MOLLIE_API_KEY);
            $opties = array(
                "amount"       => floatval($obj->bedrag),
                "method"       => Mollie_API_Object_Method::IDEAL,
                "description"  => $obj->descr,
                "redirectUrl"  => BEDANKT_PAGINA.'?orderstring='.md5(HP_SECRET.$obj->ID),
                "webhookUrl"  => WEBHOOK_URL,
                "metadata"     => array(
                    "order_id" => $obj->ID
                ),
                "issuer"       => $_POST['issuerid']
            );
            $payment = $mollie->payments->create($opties);
            $redirect_url = $payment->getPaymentUrl();
            $trans_id = $payment->id;
        } catch (Mollie_API_Exception $e) {
            $msg = 'Fout:' . $e->getMessage();
            $cssclass = 'alert alert-danger';
        }
    } else {
        $sisow = new Sisow(SISOW_MERCHANT_ID, SISOW_MERCHANT_KEY);
        $sisow->purchaseId = $factuur_id;
        $sisow->entranceCode = $obj->ID;
        $sisow->description = $obj->descr;
        $sisow->amount = floatval($obj->bedrag);
        $sisow->payment = '';
        $sisow->issuerId = $_POST['issuerid'];
        $parr['testmode'] = SISOW_TEST;
        $sisow->returnUrl = BEDANKT_PAGINA;
        $sisow->cancelUrl = GEANNULEERD_PAGINA;
        if ($ex = $sisow->TransactionRequest($parr) < 0) {
            $msg = 'Bij het aanmaken van deze transactie is een fout ontstaan.';
            $cssclass = 'alert alert-danger';
        } else {
            $trans_id = $sisow->trxId;
            $redirect_url = $sisow->issuerUrl;
        }
    }
    return array('msg' => $msg, 'css' => $cssclass, 'url' => $redirect_url, 'transid' => $trans_id);
}