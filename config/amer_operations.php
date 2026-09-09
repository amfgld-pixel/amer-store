<?php
return ['backup_disk'=>env('BACKUP_DISK','local'),'retention_days'=>(int)env('BACKUP_RETENTION_DAYS',30),'force_https'=>env('FORCE_HTTPS',true),'health_token'=>env('HEALTH_CHECK_TOKEN')];
