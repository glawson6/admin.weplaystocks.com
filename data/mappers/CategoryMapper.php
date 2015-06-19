<?php

class CategoryMapper extends BaseMapper {

    private $dbTable = 'category';

    public function InsertCategory($categoryModel) {
        try {
            parent::OpenMySqlConnection();

            $category = new Category();
            $category = $categoryModel;

            $category->setCatCode(mysql_real_escape_string($category->getCatCode()));
            $category->setCatDesc(mysql_real_escape_string($category->getCatDesc()));


            $querry = "INSERT INTO $this->dbTable (cat_code,cat_desc) 
                    VALUES('" . $category->getCatCode() . "','" . $category->getCatDesc() . "')";

            $result = mysql_query($querry, $this->connection);



            if (!$result) {

                throw new customException('insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get all category added by sachith
    public function GetAllCategory() {
        try {
            parent::OpenMySqlConnection();


            $querry = "SELECT *FROM $this->dbTable";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $categorys = array();
                $i = 0;

                while ($row = mysql_fetch_array($results)) {
                    $category = new Category();
                    $category->setId($row["id"]);
                    $category->setCatCode(stripslashes($row["cat_code"]));
                    $category->setCatDesc(stripslashes($row["cat_desc"]));

                    $categorys[$i] = $category;
                    $i++;
                }

                return $categorys;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // is duplicate code for category added by sachith
    public function IsCodeDuplicate($categoryModel) {
        try {
            parent::OpenMySqlConnection();


            $category = new Category();
            $category = $categoryModel;


            $category->setCatCode($category->getCatCode());

            $querry = "SELECT *FROM $this->dbTable WHERE cat_code='" . $category->getCatCode() . "'";

            $result = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($result)) {
                $nr = mysql_num_rows($result);
            }

            if ($nr == 0) {
                return FALSE;
            } else {
                return TRUE;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    // code must be uniqe
    public function GetCategoryByCode($categoryModel) {
        try {
            parent::OpenMySqlConnection();

            $category = new Category();
            $category = $categoryModel;


            $category->setCatCode($category->getCatCode());

            $querry = "SELECT *FROM $this->dbTable WHERE cat_code='" . $category->getCatCode() . "'";

            $results = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($results)) {
                $nr = mysql_num_rows($results);
            }

            if ($nr == 0) {
                return NULL;
            } else {

                $row = mysql_fetch_array($results);

                $category->setId($row["id"]);
                $category->setCatCode(stripslashes($row["cat_code"]));
                $category->setCatDesc(stripslashes($row["cat_desc"]));


                return $category;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

}

?>
