<?php

if (! function_exists('nemid')) {
    /** @return \Digist\Services\NemId\Service */
    function nemid()
    {
        return app(\Digist\Services\NemId\Service::class);
    }
}

if (! function_exists('is_xml')) {
    function is_xml($xml)
    {
        try {
            return (new \DOMDocument)->loadXML($xml);
        } catch (\Exception $e) {
            return false;
        }
    }
}
