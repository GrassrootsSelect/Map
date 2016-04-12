<form>
    <input type="hidden" name="id" value="<?=($data['id'])?>" />

    <label>Name</label>
    <input type="text" name="name" value="<?=($data['name'])?>" />

    <label>Abbreviation</label>
    <input type="text" name="abbreviation" value="<?=($data['abbreviation'])?>" />

    <label>ExtraText</label>
    <input type="text" name="extraText" value="<?=($data['extraText'])?>" />

    <label>Geometry</label>
    <textarea name="geometry" rows="12" cols="50"><?=($data['geometry'])?></textarea>

    <div class="button-wrapper">
        <button>save</button>
    </div>
</form>