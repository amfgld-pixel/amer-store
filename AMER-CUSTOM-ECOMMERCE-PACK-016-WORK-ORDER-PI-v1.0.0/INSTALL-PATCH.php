<?php
$root=getcwd();$web=$root.'/routes/web.php';$layout=$root.'/resources/views/layouts/admin.blade.php';$home=$root.'/resources/views/storefront/home.blade.php';
foreach([$web,$layout,$home] as $f)if(!is_file($f)){fwrite(STDERR,"Missing required file: $f\n");exit(2);}
$routes=file_get_contents($web);$line="require __DIR__.'/pack-016-commercial.php';";if(!str_contains($routes,$line))file_put_contents($web,rtrim($routes)."\n".$line."\n");
$html=file_get_contents($layout);if(!str_contains($html,"route('admin.work-orders.index')")){$anchor='<a class="{{ request()->routeIs(\'admin.purchases.*\') ? \'active\' : \'\' }}" href="{{ route(\'admin.purchases.index\') }}"><span>+</span> Purchases</a>';$insert=$anchor."\n            <a class=\"{{ request()->routeIs('admin.work-orders.*') ? 'active' : '' }}\" href=\"{{ route('admin.work-orders.index') }}\"><span>W</span> Work Orders</a>\n            <a class=\"{{ request()->routeIs('admin.proforma-invoices.*') ? 'active' : '' }}\" href=\"{{ route('admin.proforma-invoices.index') }}\"><span>PI</span> Proforma Invoices</a>";if(!str_contains($html,$anchor)){fwrite(STDERR,"Admin navigation anchor not found.\n");exit(3);}$html=str_replace($anchor,$insert,$html);file_put_contents($layout,$html);}
$blade=file_get_contents($home);$blade=str_replace('@if ($promotionBanner)','@if ($promotionBanner ?? null)',$blade);file_put_contents($home,$blade);
echo "AMER Pack-016 UTF-8-safe route, navigation and storefront patch completed.\n";

