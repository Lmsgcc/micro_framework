<div>
<a class="btn btn-primary" href="<?php echo $this->_url."controller=products&action=update"?>">New Product</a>
</div>

<div class="row col-md-12">
    <table class="col-md-6 table table-bordered">
        <thead>
            <tr>
                <th width="70%">Name</th>
                <th width="20%">Price</th>
                <th width="10%"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->_data["products"] as $value) : ?>
                <tr>
                    <td><?php echo $value["name"] ?></td>
                    <td><?php echo $value["price"] ?></td>
                    <td><a class="btn btn-info btn-sm" href='<?php echo $this->_url."controller=$this->_controller&action=update&product_id={$value['id']}"?>'>Edit</a></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>