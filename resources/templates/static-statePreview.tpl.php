<div class="left-inner-col">
    <h3>At Stake</h3>
    <p>
    <?php if ($data['raceCountdown'] != false): ?>
        We're following <?=($data['raceCountdown'])?>.
    <?php else: ?>
        We don't have any information about races in your state yet. <a href="">Help us learn more!</a>
    <?php endif; ?>
    <?php if ($data['issueCount'] > 0): ?>
        We are currently tracking <?=($data['issueCount'])?> issues important to the people of <?=($data['stateName'])?>.
    <?php else: ?>
        We don't have any information about local issues yet! <a href="">Can you tell us about one</a>?
    <?php endif; ?>
    </p>

    <h3>Get involved</h3>
    <p>
        <?=($data['stateName'])?> needs your help!
        <?php if ($data['resourceCount'] > 0): ?>
            We have <a href="#">resources available</a> for voters in this state to engage with and help progressive candidates.
        <?php else: ?>
            We do not currently have resources available for voters in this state to engage with and help progressive candidates. If you know of any resources, or are a candidate seeking office in this stae, <a href="#">let us know</a>
        <?php endif; ?>
    </p>
</div>
<aside class="right-inner-col">
    <h3>Key dates</h3>
    <?php if(count($data['dates']) > 0): ?>
        <?php foreach ($data['dates'] as $date): ?>
            <strong><?=($date->headline)?></strong>
            <span><?=(date('m/d/Y', strtotime($date->date)))?></span>
        <?php endforeach; ?>
    <?php else: ?>
        <p>We don't have any data about the timing of elections in <?=($data['stateName'])?>! Interested in <a href="#">helping us</a> get this critical information for voters?</p>
    <?php endif; ?>
</aside>

