/****************** YASR USER REVIEW *****************/

let activeTab;
let tabClass = document.getElementsByClassName('nav-tab-active');

if(tabClass.length > 0){
    activeTab = document.getElementsByClassName('nav-tab-active')[0].id;
}

if(activeTab === 'ur_general_options') {
    document.getElementById('yasr-pro-ur-default-custom-texts').addEventListener('click', function() {
        document.getElementById('yasr-pro-custom-text-comments-ratings').value         = '%total_count% votes, average %average%';
        document.getElementById('yasr-pro-custom-text-comments-ratings-archive').value = '(%total_count%)';
    });
}

/**
 * Hook into yasrBuilderDrawRankingsShortcodes and add ranking used by YASR UR
 * that need to be printed with yasrDrawRankings()
 */
wp.hooks.addFilter('yasrBuilderDrawRankingsShortcodes', 'yet-another-stars-rating', yasrUrDrawRankingShortcodes, 10);

/**
 * Add to given array the shortcode ranking used by YASR UR
 *
 * @param starRankingShortcodes Array with shortcode that need to be printed with yasrDrawRankings()
 * @return {array}
 */
function yasrUrDrawRankingShortcodes(starRankingShortcodes) {
    starRankingShortcodes.push('yasr_pro_ur_ranking');
    return starRankingShortcodes;
}