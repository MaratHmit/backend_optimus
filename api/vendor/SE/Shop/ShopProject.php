<?php

namespace SE\Shop;

use SE\DB as DB;
use SE\Exception;

class ShopProject extends Base
{
    protected $tableName = "shop_project_success";

    protected function getSettingsFetch()
    {
        $result["select"] = "sps.*, spg.name nameGroup, spi.image";

        $joins[] = [
            "type" => "left",
            "table" => 'shop_project_group spg',
            "condition" => 'sps.id_group = spg.id'
        ];

        $joins[] = [
            "type" => "left",
            "table" => 'shop_project_image spi',
            "condition" => 'spi.id_project = sps.id'
        ];

        $result["joins"] = $joins;
        return $result;
    }

    protected function correctItemsBeforeFetch($items = [])
    {
        foreach ($items as &$item) {
            $item["text"] = strip_tags($item["text"]);
            if (strpos($item['image'], "://") === false) {
                if ($item['image'] && file_exists(DOCUMENT_ROOT . '/images/rus/shopProject/' . $item['image']))
                    $item['imageUrlPreview'] = "http://".HOSTNAME. "/lib/image.php?size=64&img=images/rus/shopProject/" . $item['image'];
            } else {
                $item['imageUrlPreview'] = $item['img'];
            }

        }

        return $items;
    }

    // Получить дополнительную информацию
    protected function getAddInfo()
    {
        $result["images"] = $this->getImages();
        $result["products"] = $this->getProducts();

        return $result;
    }

    protected function correctValuesBeforeSave()
    {
        //writeLog($this->input);

        if (!$this->input["date"])
            $this->input["date"] = date("Y-m-d");

    }

    // Сохранить добавленную инфу
    protected function saveAddInfo()
    {
        if (!isset($this->input["ids"]))
            return false;

        return $this->saveImages();

    }


    // Сохранить изображения
    private function saveImages()
    {
        if (!isset($this->input["images"]))
            return true;

        try {
            $idsProjects = $this->input["ids"];
            $images = $this->input["images"];
            if ($this->isNew) {
                foreach ($images as &$image)
                    unset($image["id"]);
                unset($image);
            }
            // обновление изображений
            $idsStore = "";

            foreach ($images as $image) {
                if ($image["id"] > 0) {
                    if (!empty($idsStore))
                        $idsStore .= ",";
                    $idsStore .= $image["id"];
                    $u = new DB('shop_project_image', 'si');
                    $image["image"] = $image["imageFile"];
                    $image["sort"] = $image["sortIndex"];
                    $u->setValuesFields($image);
                    $u->save();
                }
            }
            $idsStr = implode(",", $idsProjects);
            if (!empty($idsStore)) {
                $u = new DB('shop_project_image', 'si');
                $u->where("id_project IN ($idsStr) AND NOT (id IN (?))", $idsStore)->deleteList();
            } else {
                $u = new DB('shop_project_image', 'si');
                $u->where('id_project IN (?)', $idsStr)->deleteList();
            }

            $data = [];
            foreach ($images as $image)
                if (empty($image["id"]) || ($image["id"] <= 0)) {
                    foreach ($idsProjects as $idProduct) {
                        $data[] = array('id_project' => $idProduct, 'image' => $image["imageFile"],
                            'sort' => (int)$image["sortIndex"]);
                        $newImages[] = $image["imageFile"];
                    }
                }

            if (!empty($data))
                DB::insertList('shop_project_image', $data);
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить изображения товара!";
            throw new Exception($this->error);
        }
    }

    private function getImages($idProject = null)
    {
        $result = [];
        $id = $idProject ? $idProject : $this->input["id"];
        if (!$id)
            return $result;

        $u = new DB('shop_project_image', 'si');
        $u->where('si.id_project = ?', $id);
        $u->orderBy("sort");
        $objects = $u->getList();

        foreach ($objects as $item) {
            $image = null;
            $image['id'] = $item['id'];
            $image['imageFile'] = $item['image'];
            $image['sortIndex'] = $item['sort'];
            if ($image['imageFile']) {
                if (strpos($image['imageFile'], "://") === false) {
                    $image['imageUrl'] = 'http://' . HOSTNAME . "/images/rus/shopProject/" . $image['imageFile'];
                    $image['imageUrlPreview'] = "http://" . HOSTNAME . "/lib/image.php?size=64&img=images/rus/shopProject/" . $image['imageFile'];
                } else {
                    $image['imageUrl'] = $image['imageFile'];
                    $image['imageUrlPreview'] = $image['imageFile'];
                }
            }
            if (empty($product["imageFile"])) {
                $product["imageFile"] = $image['imageFile'];
                $product["imageAlt"] = $image['imageAlt'];
            }
            $result[] = $image;
        }
        return $result;
    }

    // товары успешного проекта
    private function getProducts()
    {
        $result = [];
        $id = $idProduct ? $idProduct : $this->input["id"];
        if (!$id)
            return $result;

        $u = new DB('shop_sameprice', 'ss');
        $u->select('sp1.id id1, sp1.name name1, sp1.code code1, sp1.article article1, sp1.price price1,
                    sp2.id id2, sp2.name name2, sp2.code code2, sp2.article article2, sp2.price price2');
        $u->innerJoin('shop_price sp1', 'sp1.id = ss.id_price');
        $u->innerJoin('shop_price sp2', 'sp2.id = ss.id_acc');
        $u->where('sp1.id = ? OR sp2.id = ?', $id);
        $objects = $u->getList();
        foreach ($objects as $item) {
            $similar = null;
            $i = 1;
            if ($item['id1'] == $id)
                $i = 2;
            $similar['id'] = $item['id' . $i];
            $similar['name'] = $item['name' . $i];
            $similar['code'] = $item['code' . $i];
            $similar['article'] = $item['article' . $i];
            $similar['price'] = (real)$item['price' . $i];
            $result[] = $similar;
        }
        return $result;
    }


}

