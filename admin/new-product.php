<?php require "../admin/include/header.php"; 
$categories = Category::getAll($pdo);
?>

<?php
    $product = new Product();
    $file = "";
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $product->tensp = $_POST['tensp'];
        $product->tacgia = $_POST['tacgia'];
        $product->mota = $_POST['mota'];
        $product->dongia = $_POST['dongia'];
        
        try{
            if (empty($_FILES['file'])) {
                throw new Exception('Invalid upload');
            }
    
            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new Exception('No file uploaded');
                    break;
                default:
                    throw new Exception('An error occured');
            }

            if ($_FILES['file']['size'] > 1000000) {
                throw new Exception('File too large');
            }

            $mime_types = ['image/png', 'image/jpeg', 'image/gif'];
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($file_info, $_FILES['file']['tmp_name']);
            if (!in_array($mime_type, $mime_types)) {
                throw new Exception('Invalid file type');
            }

            $pathinfo = pathinfo($_FILES['file']['name']);
            $fname = 'hinh';
            $extension = $pathinfo['extension'];

            $file = $fname . '.' . $extension;
            $dest = '../image/' . $file;
            $i = 1;
            while (file_exists($dest)) {
                $file = $fname . $i . '.'. $extension;
                $dest = '../image/' . $file;
                $i++;
            }
            if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
                
            } else {
                throw new Exception('Unable to move file.');
            }

        }catch (Exception $e){
            echo $e->getMessage();
        }
        if($file != ""){
            $product->hinh = $file;
            if ($product->insert($pdo)) {
                if($cates = $_POST['category']){
                    foreach($cates as $cate){
                        $pro_cate = new Pro_cate();
                        $pro_cate->pro_id = $product->id;
                        $pro_cate->cate_id = $cate;
                        $pro_cate->insert($pdo);
                    }
                }
                header("Location: product.php?id={$product->id}");
                exit;
            }
        }
        
    }
?>
<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h3 class="text-center">New product</h3>
            <form method="post" class="w-50 m-auto" enctype="multipart/form-data">
                <div class="form-group mb-2">
                    <label for="tensp" class="form-label">Name <span class="text-danger">(*)</span></label>
                    <input type="text" name="tensp" class="form-control" value="" required>
                    <span class="text-danger"></span>
                </div>
                <div class="form-group mb-2">
                    <label for="tacgia" class="form-label">Author <span class="text-danger">(*)</span></label>
                    <input type="text" name="tacgia" class="form-control" value="" required>
                    <span class="text-danger"></span>
                </div>
                <div class="form-group mb-2">
                    <label  for="mota" class="form-label">Description</label>
                    <textarea class="form-control" name="mota" rows="4"></textarea>
                    <span class="text-danger"></span>
                </div>
                <div class="form-group mb-2">
                    <label for="dongia" class="form-label">Price <span class="text-danger">(*)</span></label>
                    <input type="text" name="dongia" class="form-control" value="" required>
                    <span class="text-danger"></span>
                </div>
                <div class="form-group mb-3">
                <label class="mb-2">Category</label>
                <div class="row">
                <?php foreach($categories as $category){?>
                    <div class="form-check col ms-3">
                        <input class="form-check-input" type="checkbox" value="<?=$category->maloai?>" name="category[]" id="<?= $category->maloai ?>">
                        <label class="form-check-label" for="<?= $category->maloai ?>">
                            <?= $category->tenloai ?>
                        </label>
                    </div>
                <?php }?> 
                </div>
            </div>
            <div class="form-group mb-3">
                <label class="mb-2">Image <span class="text-danger">(*)</span></label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button class="btn btn-primary form-control" type="submit">Confirm</button>
            </form>
        </div>
    </div>
</div>
<?php require "../admin/include/footer.php"?>