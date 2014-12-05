<?php
/**
/**
 * Interface that defines the methods for tracking
 * User: Diego
 * Date: 01/12/2014
 * Time: 10:33
 */

interface IKlinkCoreTelemeter {

    public function beforeOperation($coreURL, $operationName);

    public function afterOperation($coreURL, $operationName);

    public function getCoreExecutionInfo($coreURL);

    public function getOperationExecutionInfo($coreURL, $operationName);

    public function getAllExecutionInfo();

    public function exportToExcelFile($filename);

} 