<?php

class CategoryController extends BaseController {

    public function InsertCategory($catCode, $catDesc) {
        try {

            global $errors;

            if ($this->ValidationCategory($catCode, $catDesc,0)) {
                $categoryMapper = new CategoryMapper();
                $category = new Category();


                //$catCode=$_REQUEST['catCode'];
                //$catDesc=$_REQUEST['catDesc'];

                $category->setCatCode($catCode);
                $category->setCatDesc($catDesc);

                $categoryMapper->InsertCategory($category);
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function InsertCategoryManual() {
        try {

            global $errors;
            $catCode = $_REQUEST['code'];
            $catDesc = $_REQUEST['description'];
            if ($this->ValidationCategory($catCode, $catDesc, 0)) {
                $categoryMapper = new CategoryMapper();
                $category = new Category();

                $category->setCatCode($catCode);
                $category->setCatDesc($catDesc);

                $categoryMapper->InsertCategory($category);
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //Get All Category  sachith
    public function GetAllCategory() {
        try {
            $categoryMapper = new CategoryMapper();
            return $categoryMapper->GetAllCategory();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // validation Category

    private function ValidationCategory($catCode, $catDesc, $id) {
        global $errors;
        $validation = new Validation();
        if ($validation->IsEmpty($catCode)) {
            $errors['code'] = "Code can't be blank";
        } else
        if ($validation->IsNotValidLenght($catCode, 255)) {
            $errors['code'] = "Code too long";
        } else {
            $categoryMapper = new CategoryMapper();
            $category = new Category();
            $category->setCatCode($catCode);
            if ($categoryMapper->IsCodeDuplicate($category)) {
                if ($id == 0) {
                    $errors['code'] = "Code cannot be duplicate";
                } else {
                    $category = $categoryMapper->GetCategoryByCode($category);
                    if ($category) {
                        if ($category->getId() != $id) {
                            $errors['code'] = "Code cannot be duplicate";
                        }
                    }
                }
            }
        }


        if ($validation->IsEmpty($catDesc)) {
            $errors['description'] = "Description can't be blank";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>
