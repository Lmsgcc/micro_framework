
<div>
  <?php $this->LoadModule("back_btn") ?>
</div>

<div class="col-md-5">
<form method="post" action="<?php echo $this->_url."controller=".$this->_controller."&action=save" ?>" id="product_update_page">

  <?php if(!empty($this->_data["product"]["id"])): ?>
      <input type="hidden" name="product[id]" value="<?php echo $this->_data["product"]["id"] ?>"></input>
  <?php endif; ?>

  <div class="form-group">
    <label class="control-label">Product Name</label>
    <input type="text" class="form-control col-md-6" id="product_name" name="product[name]" value="<?php echo $this->_data["product"]["name"] ?? "" ?>"></input>
  </div>

  <div class="form-group">
    <label class="control-label">Price</label>
    <input type="text" class="form-control col-md-6" id="product_name" name="product[price]" value="<?php echo $this->_data["product"]["price"] ?? 0 ?>"></input>
  </div>

  <button type="submit" class="btn btn-success">Save</button>
</form>

</div>

