<!DOCTYPE html>
<html>
<head>
    <title>SEPA-Lastschrift-Mandat</title>
</head>
<body>
<h1>SEPA-Lastschrift-Mandat für eine SEPA-Basis-Lastschrift</h1>
<p>
    Alles im Rudel <br>
    Norderstraße 23 <br>
    25335 Elmshorn <br>
    Deutschland
</p>
<p>Gläubiger-Id: {{ $creditorIdentificationNumber }}</p><br>

<p>Mandatsrefernz: {{ $mandateReference }}</p>
<h1>SEPA-Lastschrift-Mandat</h1>
<p>Ich ermächtige Alles im Rudel e.V., Zahlungen von meinem Konto mittels Lastschrift einzuziehen. <br>
    Zugliech weise ich mein Kreditinsitut an, die von Alles im Rudel e.V. auf mein Konto gezogenen Lastschriften
    einzulösen. <br>
    Hinweis: ich kann innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten
    Betrags verlangen. Es gelten dabei die mit meinem Kreditinsitut vereinbarten Bedingungen.
</p>
{{ $fullName }} <br>
{{ $street }} <br>
{{ $postcode }} {{ $city }} <br>
{{ $country }} <br><br>
{{$iban}} {{ $bic }} <br><br>
{{ now()->format('d.m.Y') }} {{ $accountSignatureCity }} <br>
<img src="{{ $signature }}" height="200px" alt="Unterschrift" />
</body>
</html>