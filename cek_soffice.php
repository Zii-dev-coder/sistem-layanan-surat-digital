<?php
$sofficePath = '"C:\Program Files\LibreOffice\program\soffice.exe"';
exec($sofficePath . ' --version', $output, $returnVar);

echo "Return code: $returnVar<br>";
echo "Output:<br>";
echo implode("<br>", $output);
