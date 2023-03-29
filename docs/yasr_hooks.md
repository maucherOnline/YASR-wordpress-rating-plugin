# Hooks

- [Actions](../#actions)
- [Filters](../#filters)

## Actions

### `yasr_metabox_below_editor_add_tab`

*Use this hook to add new tabs into the metabox below the editor*


Source: [../admin/editor/YasrMetaboxBelowEditor.php](../admin/editor/YasrMetaboxBelowEditor.php), [line 57](../admin/editor/YasrMetaboxBelowEditor.php#L57-L60)

### `yasr_metabox_below_editor_content`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | 
`$multi_set` |  | 
`$n_multi_set` |  | 

Source: [../admin/editor/YasrMetaboxBelowEditor.php](../admin/editor/YasrMetaboxBelowEditor.php), [line 65](../admin/editor/YasrMetaboxBelowEditor.php#L65-L66)

### `yasr_add_content_multiset_tab_top`

*Hook here to add new content at the beginning of the div*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | int
`$set_id` |  | int

Source: [../admin/editor/YasrMetaboxBelowEditor.php](../admin/editor/YasrMetaboxBelowEditor.php), [line 199](../admin/editor/YasrMetaboxBelowEditor.php#L199-L205)

### `yasr_add_content_multiset_tab_pro`

*Hook here to add new content*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | int
`$set_id` |  | int

Source: [../admin/editor/YasrMetaboxBelowEditor.php](../admin/editor/YasrMetaboxBelowEditor.php), [line 282](../admin/editor/YasrMetaboxBelowEditor.php#L282-L288)

### `yasr_add_content_bottom_topright_metabox`

*Hook here to add content at the bottom of the metabox*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | int

Source: [../admin/editor/yasr-metabox-top-right.php](../admin/editor/yasr-metabox-top-right.php), [line 116](../admin/editor/yasr-metabox-top-right.php#L116-L121)

### `yasr_on_save_post`

*Hook here to add actions when YASR save data on save_post*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->post_id` |  | 

Source: [../admin/editor/YasrOnSavePost.php](../admin/editor/YasrOnSavePost.php), [line 56](../admin/editor/YasrOnSavePost.php#L56-L61)

### `yasr_action_on_overall_rating`

*Do action before overall rating is saved, works only in classic editor*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->post_id` |  | 
`$rating` |  | float

Source: [../admin/editor/YasrOnSavePost.php](../admin/editor/YasrOnSavePost.php), [line 100](../admin/editor/YasrOnSavePost.php#L100-L106)

### `yasr_add_tabs_on_tinypopupform`

*Use this action to add tabs inside shortcode creator for tinymce*


Source: [../admin/editor/YasrEditorHooks.php](../admin/editor/YasrEditorHooks.php), [line 216](../admin/editor/YasrEditorHooks.php#L216-L219)

### `yasr_add_content_on_tinypopupform`

*Use this action to add content inside shortcode creator*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$n_multi_set` |  | int
`$multi_set` |  | string the multiset name

Source: [../admin/editor/YasrEditorHooks.php](../admin/editor/YasrEditorHooks.php), [line 229](../admin/editor/YasrEditorHooks.php#L229-L235)

### `yasr_add_admin_scripts_begin`

*Add custom script in one of the page used by YASR, at the beginning*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$hook` |  | string

Source: [../admin/classes/YasrAdmin.php](../admin/classes/YasrAdmin.php), [line 144](../admin/classes/YasrAdmin.php#L144-L149)

### `yasr_add_admin_scripts_end`

*Add custom script in one of the page used by YASR, at the end*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$hook` |  | string

Source: [../admin/classes/YasrAdmin.php](../admin/classes/YasrAdmin.php), [line 161](../admin/classes/YasrAdmin.php#L161-L166)

### `yasr_migration_page_bottom`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$import_plugin->plugin_imported` |  | 

Source: [../admin/settings/yasr-settings-migration.php](../admin/settings/yasr-settings-migration.php), [line 63](../admin/settings/yasr-settings-migration.php#L63-L63)

### `yasr_add_stats_tab`

*Use this hook to add a tab into yasr_stats_page*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$active_tab` |  | 

Source: [../admin/settings/classes/YasrStats.php](../admin/settings/classes/YasrStats.php), [line 41](../admin/settings/classes/YasrStats.php#L41-L44)

### `yasr_stats_tab_content`

*Hook here to add new settings tab content*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$active_tab` |  | 

Source: [../admin/settings/classes/YasrStats.php](../admin/settings/classes/YasrStats.php), [line 111](../admin/settings/classes/YasrStats.php#L111-L114)

### `yasr_add_settings_tab`

*Hook here to add new settings tab*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$active_tab` |  | 

Source: [../admin/settings/classes/YasrSettings.php](../admin/settings/classes/YasrSettings.php), [line 1163](../admin/settings/classes/YasrSettings.php#L1163-L1166)

### `yasr_settings_tab_content`

*Hook here to add new settings tab content*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$active_tab` |  | 

Source: [../admin/settings/classes/YasrSettings.php](../admin/settings/classes/YasrSettings.php), [line 1238](../admin/settings/classes/YasrSettings.php#L1238-L1241)

### `yasr_right_settings_panel_box`


Source: [../admin/settings/classes/YasrSettings.php](../admin/settings/classes/YasrSettings.php), [line 1388](../admin/settings/classes/YasrSettings.php#L1388-L1388)

### `yasr_style_options_add_settings_field`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$style_options` |  | 

Source: [../admin/settings/classes/YasrSettingsStyle.php](../admin/settings/classes/YasrSettingsStyle.php), [line 46](../admin/settings/classes/YasrSettingsStyle.php#L46-L46)

### `yasr_ur_add_custom_form_fields`


Source: [../yasr_pro/public/classes/YasrProCommentForm.php](../yasr_pro/public/classes/YasrProCommentForm.php), [line 170](../yasr_pro/public/classes/YasrProCommentForm.php#L170-L170)

### `yasr_ur_save_custom_form_fields`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$comment_id` |  | 

Source: [../yasr_pro/public/classes/YasrProCommentForm.php](../yasr_pro/public/classes/YasrProCommentForm.php), [line 495](../yasr_pro/public/classes/YasrProCommentForm.php#L495-L495)

### `yasr_ur_do_content_after_save_commentmeta`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$comment_id` |  | 

Source: [../yasr_pro/public/classes/YasrProCommentForm.php](../yasr_pro/public/classes/YasrProCommentForm.php), [line 504](../yasr_pro/public/classes/YasrProCommentForm.php#L504-L504)

### `yasr_fs_loaded`


Source: [../yet-another-stars-rating.php](../yet-another-stars-rating.php), [line 82](../yet-another-stars-rating.php#L82-L82)

### `yasr_action_on_visitor_vote`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$array_action_visitor_vote` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](../includes/shortcodes/classes/YasrShortcodesAjax.php), [line 86](../includes/shortcodes/classes/YasrShortcodesAjax.php#L86-L86)

### `yasr_action_on_visitor_multiset_vote`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$array_action_visitor_multiset_vote` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](../includes/shortcodes/classes/YasrShortcodesAjax.php), [line 285](../includes/shortcodes/classes/YasrShortcodesAjax.php#L285-L285)

### `yasr_enqueue_assets_shortcode`


Source: [../includes/shortcodes/classes/YasrShortcode.php](../includes/shortcodes/classes/YasrShortcode.php), [line 157](../includes/shortcodes/classes/YasrShortcode.php#L157-L157)

### `yasr_add_front_script_css`


Source: [../includes/classes/YasrScriptsLoader.php](../includes/classes/YasrScriptsLoader.php), [line 118](../includes/classes/YasrScriptsLoader.php#L118-L118)

### `yasr_add_front_script_js`


Source: [../includes/classes/YasrScriptsLoader.php](../includes/classes/YasrScriptsLoader.php), [line 127](../includes/classes/YasrScriptsLoader.php#L127-L127)

### `yasr_display_posts_top`

*hook here to add content at the beginning of yasr_display_posts*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | 

Source: [../templates/content.php](../templates/content.php), [line 25](../templates/content.php#L25-L28)

### `yasr_display_posts_bottom`

*hook here to add content at the end of yasr_display_posts*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | 

Source: [../templates/content.php](../templates/content.php), [line 58](../templates/content.php#L58-L61)

## Filters

### `yasr_feature_locked`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'<span class="dashicons dashicons-lock" title="' . esc_attr($text) . '"></span>'` |  | 
`10` |  | 
`1` |  | 

Source: [../admin/yasr-admin-init.php](../admin/yasr-admin-init.php), [line 33](../admin/yasr-admin-init.php#L33-L36)

### `yasr_feature_locked_html_attribute`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'disabled'` |  | 
`10` |  | 
`1` |  | 

Source: [../admin/yasr-admin-init.php](../admin/yasr-admin-init.php), [line 38](../admin/yasr-admin-init.php#L38-L38)

### `yasr_feature_locked_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$upgrade_text` |  | 
`10` |  | 
`1` |  | 

Source: [../admin/yasr-admin-init.php](../admin/yasr-admin-init.php), [line 53](../admin/yasr-admin-init.php#L53-L53)

### `yasr_settings_select_ranking`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$source_array` |  | 

Source: [../admin/settings/classes/YasrSettingsRankings.php](../admin/settings/classes/YasrSettingsRankings.php), [line 60](../admin/settings/classes/YasrSettingsRankings.php#L60-L60)

### `yasr_filter_style_options`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$style_options` |  | 

Source: [../admin/settings/classes/YasrSettingsStyle.php](../admin/settings/classes/YasrSettingsStyle.php), [line 37](../admin/settings/classes/YasrSettingsStyle.php#L37-L37)

### `yasr_sanitize_style_options`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$style_options` |  | 

Source: [../admin/settings/classes/YasrSettingsStyle.php](../admin/settings/classes/YasrSettingsStyle.php), [line 265](../admin/settings/classes/YasrSettingsStyle.php#L265-L265)

### `yasr_ur_display_custom_fields`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$comment_id` |  | 

Source: [../yasr_pro/public/classes/YasrProCommentForm.php](../yasr_pro/public/classes/YasrProCommentForm.php), [line 284](../yasr_pro/public/classes/YasrProCommentForm.php#L284-L284)

### `yasr_filter_schema_jsonld`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$review_choosen` |  | 

Source: [../public/classes/YasrRichSnippets.php](../public/classes/YasrRichSnippets.php), [line 73](../public/classes/YasrRichSnippets.php#L73-L73)

### `yasr_filter_existing_schema`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$rich_snippet` |  | 
`$rich_snippet_data` |  | 

Source: [../public/classes/YasrRichSnippets.php](../public/classes/YasrRichSnippets.php), [line 132](../public/classes/YasrRichSnippets.php#L132-L132)

### `yasr_filter_schema_title`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | 

Source: [../public/classes/YasrRichSnippets.php](../public/classes/YasrRichSnippets.php), [line 164](../public/classes/YasrRichSnippets.php#L164-L164)

### `yasr_auto_insert_disable`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_excluded` |  | 
`$content` |  | 

Source: [../public/classes/YasrPublicFilters.php](../public/classes/YasrPublicFilters.php), [line 62](../public/classes/YasrPublicFilters.php#L62-L62)

### `yasr_auto_insert_exclude_cpt`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$excluded_cpt` |  | 

Source: [../public/classes/YasrPublicFilters.php](../public/classes/YasrPublicFilters.php), [line 92](../public/classes/YasrPublicFilters.php#L92-L92)

### `yasr_title_vv_widget`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$vv_widget` |  | 
`$stored_votes` |  | 

Source: [../public/classes/YasrPublicFilters.php](../public/classes/YasrPublicFilters.php), [line 276](../public/classes/YasrPublicFilters.php#L276-L276)

### `yasr_title_overall_widget`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$overall_widget` |  | 
`$overall_rating` |  | 

Source: [../public/classes/YasrPublicFilters.php](../public/classes/YasrPublicFilters.php), [line 313](../public/classes/YasrPublicFilters.php#L313-L313)

### `yasr_overall_rating_shortcode`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$shortcode_html` |  | 
`$overall_attributes` |  | 

Source: [../includes/shortcodes/classes/YasrOverallRating.php](../includes/shortcodes/classes/YasrOverallRating.php), [line 52](../includes/shortcodes/classes/YasrOverallRating.php#L52-L52)

### `yasr_cstm_text_before_overall`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->overall_rating` |  | 

Source: [../includes/shortcodes/classes/YasrOverallRating.php](../includes/shortcodes/classes/YasrOverallRating.php), [line 124](../includes/shortcodes/classes/YasrOverallRating.php#L124-L124)

### `yasr_vv_cookie`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'yasr_visitor_vote_cookie'` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](../includes/shortcodes/classes/YasrShortcodesAjax.php), [line 156](../includes/shortcodes/classes/YasrShortcodesAjax.php#L156-L156)

### `yasr_vv_updated_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$rating_saved_text` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](../includes/shortcodes/classes/YasrShortcodesAjax.php), [line 169](../includes/shortcodes/classes/YasrShortcodesAjax.php#L169-L169)

### `yasr_vv_saved_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$rating_saved_text` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](../includes/shortcodes/classes/YasrShortcodesAjax.php), [line 172](../includes/shortcodes/classes/YasrShortcodesAjax.php#L172-L172)

### `yasr_vv_rating_error_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$error_text` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](../includes/shortcodes/classes/YasrShortcodesAjax.php), [line 195](../includes/shortcodes/classes/YasrShortcodesAjax.php#L195-L195)

### `yasr_mv_cookie`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'yasr_multi_visitor_cookie'` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](../includes/shortcodes/classes/YasrShortcodesAjax.php), [line 371](../includes/shortcodes/classes/YasrShortcodesAjax.php#L371-L371)

### `yasr_mv_saved_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`__('Rating Saved', 'yet-another-stars-rating')` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](../includes/shortcodes/classes/YasrShortcodesAjax.php), [line 380](../includes/shortcodes/classes/YasrShortcodesAjax.php#L380-L380)

### `yasr_filter_ranking_request`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`false` |  | 
`$request` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](../includes/shortcodes/classes/YasrShortcodesAjax.php), [line 563](../includes/shortcodes/classes/YasrShortcodesAjax.php#L563-L563)

### `yasr_add_sources_ranking_request`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$data_to_return` |  | 
`$source` |  | 
`$request` |  | 
`$sql_params` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](../includes/shortcodes/classes/YasrShortcodesAjax.php), [line 614](../includes/shortcodes/classes/YasrShortcodesAjax.php#L614-L614)

### `yasr_mv_cookie`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'yasr_multi_visitor_cookie'` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorMultiSet.php](../includes/shortcodes/classes/YasrVisitorMultiSet.php), [line 113](../includes/shortcodes/classes/YasrVisitorMultiSet.php#L113-L113)

### `yasr_must_sign_in`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`''` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorMultiSet.php](../includes/shortcodes/classes/YasrVisitorMultiSet.php), [line 167](../includes/shortcodes/classes/YasrVisitorMultiSet.php#L167-L167)

### `yasr_vv_ro_shortcode`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$shortcode_html` |  | 
`$stored_votes` |  | 
`$this->post_id` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](../includes/shortcodes/classes/YasrVisitorVotes.php), [line 109](../includes/shortcodes/classes/YasrVisitorVotes.php#L109-L109)

### `yasr_vv_cookie`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'yasr_visitor_vote_cookie'` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](../includes/shortcodes/classes/YasrVisitorVotes.php), [line 119](../includes/shortcodes/classes/YasrVisitorVotes.php#L119-L119)

### `yasr_cstm_text_already_voted`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$rating` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](../includes/shortcodes/classes/YasrVisitorVotes.php), [line 193](../includes/shortcodes/classes/YasrVisitorVotes.php#L193-L193)

### `yasr_must_sign_in`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`''` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](../includes/shortcodes/classes/YasrVisitorVotes.php), [line 205](../includes/shortcodes/classes/YasrVisitorVotes.php#L205-L205)

### `yasr_cstm_text_before_vv`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$number_of_votes` |  | 
`$average_rating` |  | 
`$this->unique_id` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](../includes/shortcodes/classes/YasrVisitorVotes.php), [line 231](../includes/shortcodes/classes/YasrVisitorVotes.php#L231-L231)

### `yasr_cstm_text_after_vv`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$number_of_votes` |  | 
`$average_rating` |  | 
`$this->unique_id` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](../includes/shortcodes/classes/YasrVisitorVotes.php), [line 274](../includes/shortcodes/classes/YasrVisitorVotes.php#L274-L274)

### `yasr_vv_shortcode`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$shortcode_html` |  | 
`$stored_votes` |  | 
`$this->post_id` |  | 
`$this->starSize()` |  | 
`$this->readonly` |  | 
`$this->ajax_nonce_visitor` |  | 
`$this->is_singular` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](../includes/shortcodes/classes/YasrVisitorVotes.php), [line 377](../includes/shortcodes/classes/YasrVisitorVotes.php#L377-L386)

### `yasr_tr_rankings_atts`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`false` |  | 
`$atts` |  | 

Source: [../includes/shortcodes/classes/YasrNoStarsRankings.php](../includes/shortcodes/classes/YasrNoStarsRankings.php), [line 36](../includes/shortcodes/classes/YasrNoStarsRankings.php#L36-L36)

### `yasr_tu_rankings_atts`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`false` |  | 
`$atts` |  | 

Source: [../includes/shortcodes/classes/YasrNoStarsRankings.php](../includes/shortcodes/classes/YasrNoStarsRankings.php), [line 63](../includes/shortcodes/classes/YasrNoStarsRankings.php#L63-L63)

### `yasr_tu_rankings_display`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$user_data->user_login` |  | 
`$user_data` |  | 

Source: [../includes/shortcodes/classes/YasrNoStarsRankings.php](../includes/shortcodes/classes/YasrNoStarsRankings.php), [line 124](../includes/shortcodes/classes/YasrNoStarsRankings.php#L124-L124)

### `yasr_size_ranking`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'medium'` |  | 

Source: [../includes/shortcodes/classes/YasrShortcode.php](../includes/shortcodes/classes/YasrShortcode.php), [line 88](../includes/shortcodes/classes/YasrShortcode.php#L88-L88)

### `yasr_ov_rankings_atts`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->shortcode_name` |  | 
`$atts` |  | 

Source: [../includes/shortcodes/classes/YasrRankings.php](../includes/shortcodes/classes/YasrRankings.php), [line 54](../includes/shortcodes/classes/YasrRankings.php#L54-L54)

### `yasr_vv_rankings_atts`

*Hook here to use shortcode atts.*

If not used, will work with no support for atts

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->shortcode_name` |  | 
`$atts` | `string\|array` | Shortcode atts

Source: [../includes/shortcodes/classes/YasrRankings.php](../includes/shortcodes/classes/YasrRankings.php), [line 77](../includes/shortcodes/classes/YasrRankings.php#L77-L84)

### `yasr_multi_set_ranking_atts`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->shortcode_name` |  | 
`$atts` |  | 

Source: [../includes/shortcodes/classes/YasrRankings.php](../includes/shortcodes/classes/YasrRankings.php), [line 112](../includes/shortcodes/classes/YasrRankings.php#L112-L112)

### `yasr_visitor_multi_set_ranking_atts`

*Hook here to use shortcode atts.*

If not used, shortcode will works only with setId param

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->shortcode_name` |  | 
`$atts` | `string\|array` | Shortcode atts

Source: [../includes/shortcodes/classes/YasrRankings.php](../includes/shortcodes/classes/YasrRankings.php), [line 137](../includes/shortcodes/classes/YasrRankings.php#L137-L144)

### `yasr_filter_ip`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$ip` |  | 

Source: [../includes/yasr-includes-functions.php](../includes/yasr-includes-functions.php), [line 148](../includes/yasr-includes-functions.php#L148-L148)

### `yasr_rest_rankings_args`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$args` |  | 

Source: [../includes/rest/classes/YasrCustomEndpoint.php](../includes/rest/classes/YasrCustomEndpoint.php), [line 146](../includes/rest/classes/YasrCustomEndpoint.php#L146-L146)

### `yasr_rest_sanitize`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$key` |  | 
`$param` |  | 

Source: [../includes/rest/classes/YasrCustomEndpoint.php](../includes/rest/classes/YasrCustomEndpoint.php), [line 277](../includes/rest/classes/YasrCustomEndpoint.php#L277-L277)

### `yasr_rankings_query_ov`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$atts` |  | 

Source: [../includes/classes/YasrDB.php](../includes/classes/YasrDB.php), [line 330](../includes/classes/YasrDB.php#L330-L330)

### `yasr_rankings_query_vv`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$atts` |  | 
`$ranking` |  | 

Source: [../includes/classes/YasrDB.php](../includes/classes/YasrDB.php), [line 374](../includes/classes/YasrDB.php#L374-L374)

### `yasr_rankings_query_tu`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$atts` |  | 

Source: [../includes/classes/YasrDB.php](../includes/classes/YasrDB.php), [line 424](../includes/classes/YasrDB.php#L424-L424)

### `yasr_rankings_multi_query`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$sql_atts` |  | 
`$set_id` |  | 

Source: [../includes/classes/YasrDB.php](../includes/classes/YasrDB.php), [line 469](../includes/classes/YasrDB.php#L469-L469)

### `yasr_rankings_query_tr`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$atts` |  | 

Source: [../includes/classes/YasrDB.php](../includes/classes/YasrDB.php), [line 523](../includes/classes/YasrDB.php#L523-L523)

### `yasr_rankings_multivv_query`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$sql_atts` |  | 
`$ranking` |  | 
`$set_id` |  | 

Source: [../includes/classes/YasrDB.php](../includes/classes/YasrDB.php), [line 574](../includes/classes/YasrDB.php#L574-L574)

### `yasr_custom_loader`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$yasr_visitor_votes_loader` |  | 

Source: [../includes/classes/YasrScriptsLoader.php](../includes/classes/YasrScriptsLoader.php), [line 59](../includes/classes/YasrScriptsLoader.php#L59-L59)

### `yasr_custom_loader_url`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`YASR_IMG_DIR . 'loader.gif'` |  | 

Source: [../includes/classes/YasrScriptsLoader.php](../includes/classes/YasrScriptsLoader.php), [line 63](../includes/classes/YasrScriptsLoader.php#L63-L63)

### `yasr_gutenberg_constants`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$constants_array` |  | 

Source: [../includes/classes/YasrScriptsLoader.php](../includes/classes/YasrScriptsLoader.php), [line 525](../includes/classes/YasrScriptsLoader.php#L525-L525)


<p align="center"><a href="https://github.com/pronamic/wp-documentor"><img src="https://cdn.jsdelivr.net/gh/pronamic/wp-documentor@main/logos/pronamic-wp-documentor.svgo-min.svg" alt="Pronamic WordPress Documentor" width="32" height="32"></a><br><em>Generated by <a href="https://github.com/pronamic/wp-documentor">Pronamic WordPress Documentor</a> <code>1.2.0</code></em><p>

