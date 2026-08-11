<?php

return [
    'rate_limit' => (int) env('CONTACT_RATE_LIMIT', 10),
    'origin_rate_limit' => (int) env('CONTACT_ORIGIN_RATE_LIMIT', 60),

];
