<?php
use Feggu\Core\Events\PatientCreated;
use Feggu\Core\Partner\Models\FegguConsultation;






if (!function_exists('au_event_customer_created') && !in_array('au_event_customer_created', config('helper_except', []))) {
    /**
     * Process customer event
     *
     * @param FegguConsultation $patien
     * @return void [type]          [return description]
     */
    function au_event_customer_created(FegguConsultation $patien)
    {
        PatientCreated::dispatch($patien);
    }
}

