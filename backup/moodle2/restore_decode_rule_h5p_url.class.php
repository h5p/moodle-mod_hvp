<?php
class restore_decode_rule_h5p_url extends restore_decode_rule {

    /**
     * This class exists so that restore can run over URLs to H5P Scripts which
     * do not have an id referencing the course / course module - their soley static
     * scripts used across the activity - e.g. h5p-resizer.js - this is included when
     * embedding a h5p activity, but not specific to an activity - the url still needs
     * to be replaced with one relevant to the new restore site.
     *
     * @param $linkname
     * @param $urltemplate
     * @param $mappings
     * @return array
     */
    protected function validate_params($linkname, $urltemplate, $mappings) {
        return [];
    }
}
