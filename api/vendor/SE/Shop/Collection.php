<?php

namespace SE\Shop;

use SE\DB as DB;
use SE\Exception;

class Collection extends Base
{
    protected $tableName = "shop_collection";

    protected $sortBy = "sort";
    protected $sortOrder = "asc";

    protected function getSettingsFetch()
    {
        $result["select"] = "sc.*, scg.name groupName";
        $joins[] = array(
            "type" => "left",
            "table" => 'shop_collection_group scg',
            "condition" => 'sc.id_group = scg.id'
        );
        $result["joins"] = $joins;
        return $result;
    }



}
