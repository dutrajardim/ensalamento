<?php
namespace App\Traits;

trait QueryParamtersTrait {


    /**
     * Converte string de filtros para array
     *
     * @param string $filters
     * @param array $allowedFields
     * @param array $allowedOperators
     * @return array
     */
    public function getFilters(string $filters, array $allowedFields, array $allowedOperators = [ ':', '>', '<','<>']) {
        $wheres = [];
        $arrFilters = explode(",", $filters);

        foreach($arrFilters as $filter) {
            $parts = preg_split("/([:<>]|<>)/i",$filter,-1,PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
            
            if (count($parts) == 3 && 
            in_array($parts[0],$allowedFields) && 
            in_array($parts[1],$allowedOperators)) {
                
                if ($parts[1] == ':') {
                    $parts[1] = '=';
                }
                
                array_push($wheres,$parts);
            }

        }
        return $wheres;
    }
}
