<div id="map_wrapper">
    <form class="lookup-box-wrapper"><!-- add class: zoom-hide-layer -->
        <p>Zoom to your state with your zip code.</p>
        <input placeholder="enter your zip code" type="text" name="addr" />
        <button class="do-address-lookup-button">go</button>
        <span>privacy note: we do not store this information anywhere.</span>
    </form>

    <div class="map-help-wrapper help-wrapper arrow-right zoom-hide-layer">
        <p>Tap your state to see information about primary voting dates, and a political overview</p>
        <button class="map-help-dismiss">got it</button>
    </div>

    <div style="display:none;" id="info-box">
        <div class="state-name-wrapper">
            <span class="state-name"></span>
        </div>
        <div class="loading-message">
            loading...<br>
            <img src="/images/loading.gif" />
        </div>
        <div class="info-content" style="display:none;">
        </div>
        <div class="button-wrapper">
            <button id="info-box-more" style="display:none;">more info</button>
            <button id="info-box-closer" style="display:none; background-color: #a20000;">close</button>
        </div>
    </div>
</div>