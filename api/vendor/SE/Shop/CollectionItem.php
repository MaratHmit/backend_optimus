<?php

namespace SE\Shop;

use SE\DB as DB;
use SE\Exception;

class CollectionItem extends Base
{
    protected $tableName = "shop_collection_item";
    protected $sortBy = "sort";
    protected $sortOrder = "asc";

    protected function getSettingsFetch()
    {
        $result["select"] = "sci.*, sc.name collectionName";
        $joins[] = array(
            "type" => "left",
            "table" => 'shop_collection sc',
            "condition" => 'sci.id_collection = sc.id'
        );
        $result["joins"] = $joins;
        return $result;
    }

    public function fetch()
    {
        parent::fetch();

        foreach($this->result['items'] as &$item){
            if ($item['image']) {
                if (strpos($item['image'], "://") === false) {
                    $item['imageUrl'] = 'http://' . $this->hostname . "/images/rus/collections/" . $item['image'];
                    $item['imageUrlPreview'] = "http://{$this->hostname}/lib/image.php?size=64&img=images/rus/collections/" . $item['image'];
                } else {
                    $item['imageUrl'] = $item['image'];
                    $item['imageUrlPreview'] = $item['image'];
                }
            }
        }
        return $this->result["items"];
    }

}

