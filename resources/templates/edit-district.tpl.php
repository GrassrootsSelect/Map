<form>
    <input type="hidden" name="id" value="<?=($data['id'])?>" />

    <label>Number</label>
    <input type="text" name="number" value="<?=($data['number'])?>" />

    <label>State</label>
    <select name="stateId">
        <?php foreach($data['states'] as $state): ?>
            <option <?=($state['id'] === $data['stateId'] ? 'selected' : '')?> value="<?=($state['id'])?>"><?=($state['name'])?></option>
        <?php endforeach; ?>
    </select>

    <label>Scope</label>
    <input type="text" name="scope" value="<?=($data['scope'])?>" />

    <div class="button-wrapper">
        <button>save</button>
    </div>
</form>