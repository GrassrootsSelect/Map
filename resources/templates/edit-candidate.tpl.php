<form>
    <input type="hidden" name="id" value="<?=($data['id'])?>" />

    <label>Name</label>
    <input type="text" name="name" value="<?=($data['name'])?>" />

    <label>Description</label>
    <input type="text" name="description" value="<?=($data['description'])?>" />

    <label>Party</label>
    <input type="text" name="party" value="<?=($data['party'])?>" />

    <label>Image</label>
    <input type="text" name="image" value="<?=($data['image'])?>" />

    <label>State</label>
    <select name="stateId">
        <?php foreach($data['states'] as $state): ?>
            <option <?=($state['id'] === $data['stateId'] ? 'selected' : '')?> value="<?=($state['id'])?>"><?=($state['name'])?></option>
        <?php endforeach; ?>
    </select>

    <label>District</label>
    <select name="districtId">
        <?php foreach($data['districts'] as $district): ?>
            <option <?=($district['id'] === $data['districtId'] ? 'selected' : '')?> value="<?=($district['id'])?>"><?=($district['number'])?></option>
        <?php endforeach; ?>
    </select>

    <label>Locale (city)</label>
    <select name="localeId">
        <?php foreach($data['locales'] as $locale): ?>
            <option <?=($locale['id'] === $data['localeId'] ? 'selected' : '')?> value="<?=($locale['id'])?>"><?=($locale['name'])?></option>
        <?php endforeach; ?>
    </select>

    <label>Scope</label>
    <input type="text" name="scope" value="<?=($data['scope'])?>" />

    <label>Executive</label>
    <div class="radios">
        <label>
            <input type="radio" <?=($data['executive'] == '1' ? 'checked' : '')?> name="executive" value="1" />
            yes
        </label>
        <label>
            <input type="radio" <?=($data['executive'] == '1' ? 'checked' : '')?> name="executive" value="0" />
            no
        </label>
    </div>



    <div class="button-wrapper">
        <button>save</button>
    </div>
</form>