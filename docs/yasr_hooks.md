# Hooks

- [Actions](#actions)
- [Filters](#filters)

## Actions

### `yasr_metabox_below_editor_add_tab`

*Use this hook to add new tabs into the metabox below the editor*


Source: [../admin/editor/YasrMetaboxBelowEditor.php](admin/editor/YasrMetaboxBelowEditor.php), [line 57](admin/editor/YasrMetaboxBelowEditor.php#L57-L60)

### `yasr_metabox_below_editor_content`

*Use this hook to add new content into the metabox below the editor*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | int
`$multi_set` |  | mixed
`$n_multi_set` |  | mixed

**Changelog**

Version | Description
------- | -----------
`1.5.2` | 

Source: [../admin/editor/YasrMetaboxBelowEditor.php](admin/editor/YasrMetaboxBelowEditor.php), [line 65](admin/editor/YasrMetaboxBelowEditor.php#L65-L74)

### `yasr_add_content_multiset_tab_top`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | 
`$set_id` |  | 

Source: [../admin/editor/YasrMetaboxBelowEditor.php](admin/editor/YasrMetaboxBelowEditor.php), [line 234](admin/editor/YasrMetaboxBelowEditor.php#L234-L234)

### `yasr_add_content_multiset_tab_pro`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | 
`$set_id` |  | 

Source: [../admin/editor/YasrMetaboxBelowEditor.php](admin/editor/YasrMetaboxBelowEditor.php), [line 309](admin/editor/YasrMetaboxBelowEditor.php#L309-L309)

### `yasr_add_content_bottom_topright_metabox`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | 

Source: [../admin/editor/yasr-metabox-top-right.php](admin/editor/yasr-metabox-top-right.php), [line 117](admin/editor/yasr-metabox-top-right.php#L117-L117)

### `yasr_on_save_post`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->post_id` |  | 

Source: [../admin/editor/YasrOnSavePost.php](admin/editor/YasrOnSavePost.php), [line 56](admin/editor/YasrOnSavePost.php#L56-L56)

### `yasr_action_on_overall_rating`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->post_id` |  | 
`$rating` |  | 

Source: [../admin/editor/YasrOnSavePost.php](admin/editor/YasrOnSavePost.php), [line 96](admin/editor/YasrOnSavePost.php#L96-L96)

### `yasr_add_tabs_on_tinypopupform`


Source: [../admin/editor/YasrEditorHooks.php](admin/editor/YasrEditorHooks.php), [line 212](admin/editor/YasrEditorHooks.php#L212-L212)

### `yasr_add_content_on_tinypopupform`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$n_multi_set` |  | 
`$multi_set` |  | 

Source: [../admin/editor/YasrEditorHooks.php](admin/editor/YasrEditorHooks.php), [line 223](admin/editor/YasrEditorHooks.php#L223-L223)

### `yasr_add_admin_scripts_begin`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$hook` |  | 

Source: [../admin/yasr-admin-functions.php](admin/yasr-admin-functions.php), [line 90](admin/yasr-admin-functions.php#L90-L90)

### `yasr_add_admin_scripts_end`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$hook` |  | 

Source: [../admin/yasr-admin-functions.php](admin/yasr-admin-functions.php), [line 102](admin/yasr-admin-functions.php#L102-L102)

### `yasr_settings_tab_content`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$active_tab` |  | 

Source: [../admin/settings/yasr-settings-page.php](admin/settings/yasr-settings-page.php), [line 88](admin/settings/yasr-settings-page.php#L88-L88)

### `yasr_add_stats_tab`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$active_tab` |  | 

Source: [../admin/settings/yasr-stats-page.php](admin/settings/yasr-stats-page.php), [line 63](admin/settings/yasr-stats-page.php#L63-L63)

### `yasr_settings_check_active_tab`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$active_tab` |  | 

Source: [../admin/settings/yasr-stats-page.php](admin/settings/yasr-stats-page.php), [line 130](admin/settings/yasr-stats-page.php#L130-L130)

### `yasr_style_options_add_settings_field`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$style_options` |  | 

Source: [../admin/settings/aspect_style/yasr-settings-style-functions.php](admin/settings/aspect_style/yasr-settings-style-functions.php), [line 49](admin/settings/aspect_style/yasr-settings-style-functions.php#L49-L49)

### `yasr_add_settings_tab`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$active_tab` |  | 

Source: [../admin/settings/yasr-settings-functions-misc.php](admin/settings/yasr-settings-functions-misc.php), [line 62](admin/settings/yasr-settings-functions-misc.php#L62-L62)

### `yasr_right_settings_panel_box`


Source: [../admin/settings/yasr-settings-functions-misc.php](admin/settings/yasr-settings-functions-misc.php), [line 533](admin/settings/yasr-settings-functions-misc.php#L533-L533)

### `yasr_migration_page_bottom`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$import_plugin->plugin_imported` |  | 

Source: [../admin/settings/yasr-settings-migration-page.php](admin/settings/yasr-settings-migration-page.php), [line 57](admin/settings/yasr-settings-migration-page.php#L57-L57)

### `yasr_ur_add_custom_form_fields`


Source: [../yasr_pro/public/classes/YasrProCommentForm.php](yasr_pro/public/classes/YasrProCommentForm.php), [line 170](yasr_pro/public/classes/YasrProCommentForm.php#L170-L170)

### `yasr_ur_save_custom_form_fields`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$comment_id` |  | 

Source: [../yasr_pro/public/classes/YasrProCommentForm.php](yasr_pro/public/classes/YasrProCommentForm.php), [line 495](yasr_pro/public/classes/YasrProCommentForm.php#L495-L495)

### `yasr_ur_do_content_after_save_commentmeta`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$comment_id` |  | 

Source: [../yasr_pro/public/classes/YasrProCommentForm.php](yasr_pro/public/classes/YasrProCommentForm.php), [line 504](yasr_pro/public/classes/YasrProCommentForm.php#L504-L504)

### `yasr_fs_loaded`


Source: [../yet-another-stars-rating.php](yet-another-stars-rating.php), [line 82](yet-another-stars-rating.php#L82-L82)

### `yasr_action_on_visitor_vote`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$array_action_visitor_vote` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](includes/shortcodes/classes/YasrShortcodesAjax.php), [line 86](includes/shortcodes/classes/YasrShortcodesAjax.php#L86-L86)

### `yasr_action_on_visitor_multiset_vote`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$array_action_visitor_multiset_vote` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](includes/shortcodes/classes/YasrShortcodesAjax.php), [line 349](includes/shortcodes/classes/YasrShortcodesAjax.php#L349-L349)

### `yasr_enqueue_assets_shortcode`


Source: [../includes/shortcodes/classes/YasrShortcode.php](includes/shortcodes/classes/YasrShortcode.php), [line 158](includes/shortcodes/classes/YasrShortcode.php#L158-L158)

### `yasr_add_front_script_css`


Source: [../includes/classes/YasrScriptsLoader.php](includes/classes/YasrScriptsLoader.php), [line 118](includes/classes/YasrScriptsLoader.php#L118-L118)

### `yasr_add_front_script_js`


Source: [../includes/classes/YasrScriptsLoader.php](includes/classes/YasrScriptsLoader.php), [line 127](includes/classes/YasrScriptsLoader.php#L127-L127)

## Filters

### `yasr_feature_locked`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'<span class="dashicons dashicons-lock" title="' . esc_attr($text) . '"></span>'` |  | 
`10` |  | 
`1` |  | 

Source: [../admin/yasr-admin-init.php](admin/yasr-admin-init.php), [line 44](admin/yasr-admin-init.php#L44-L47)

### `yasr_feature_locked_html_attribute`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'disabled'` |  | 
`10` |  | 
`1` |  | 

Source: [../admin/yasr-admin-init.php](admin/yasr-admin-init.php), [line 49](admin/yasr-admin-init.php#L49-L49)

### `yasr_filter_style_options`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$style_options` |  | 

Source: [../admin/settings/aspect_style/yasr-settings-style-functions.php](admin/settings/aspect_style/yasr-settings-style-functions.php), [line 40](admin/settings/aspect_style/yasr-settings-style-functions.php#L40-L40)

### `yasr_sanitize_style_options`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$style_options` |  | 

Source: [../admin/settings/aspect_style/yasr-settings-style-functions.php](admin/settings/aspect_style/yasr-settings-style-functions.php), [line 136](admin/settings/aspect_style/yasr-settings-style-functions.php#L136-L136)

### `yasr_settings_select_ranking`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$source_array` |  | 

Source: [../admin/settings/classes/YasrSettingsRankings.php](admin/settings/classes/YasrSettingsRankings.php), [line 62](admin/settings/classes/YasrSettingsRankings.php#L62-L62)

### `yasr_ur_display_custom_fields`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$comment_id` |  | 

Source: [../yasr_pro/public/classes/YasrProCommentForm.php](yasr_pro/public/classes/YasrProCommentForm.php), [line 284](yasr_pro/public/classes/YasrProCommentForm.php#L284-L284)

### `yasr_filter_schema_jsonld`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$review_choosen` |  | 

Source: [../public/classes/YasrRichSnippets.php](public/classes/YasrRichSnippets.php), [line 73](public/classes/YasrRichSnippets.php#L73-L73)

### `yasr_filter_existing_schema`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$rich_snippet` |  | 
`$rich_snippet_data` |  | 

Source: [../public/classes/YasrRichSnippets.php](public/classes/YasrRichSnippets.php), [line 132](public/classes/YasrRichSnippets.php#L132-L132)

### `yasr_filter_schema_title`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_id` |  | 

Source: [../public/classes/YasrRichSnippets.php](public/classes/YasrRichSnippets.php), [line 164](public/classes/YasrRichSnippets.php#L164-L164)

### `yasr_auto_insert_disable`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$post_excluded` |  | 
`$content` |  | 

Source: [../public/classes/YasrPublicFilters.php](public/classes/YasrPublicFilters.php), [line 53](public/classes/YasrPublicFilters.php#L53-L53)

### `yasr_auto_insert_exclude_cpt`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$excluded_cpt` |  | 

Source: [../public/classes/YasrPublicFilters.php](public/classes/YasrPublicFilters.php), [line 83](public/classes/YasrPublicFilters.php#L83-L83)

### `yasr_title_vv_widget`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$vv_widget` |  | 
`$stored_votes` |  | 

Source: [../public/classes/YasrPublicFilters.php](public/classes/YasrPublicFilters.php), [line 273](public/classes/YasrPublicFilters.php#L273-L273)

### `yasr_title_overall_widget`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$overall_widget` |  | 
`$overall_rating` |  | 

Source: [../public/classes/YasrPublicFilters.php](public/classes/YasrPublicFilters.php), [line 310](public/classes/YasrPublicFilters.php#L310-L310)

### `yasr_overall_rating_shortcode`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$shortcode_html` |  | 
`$overall_attributes` |  | 

Source: [../includes/shortcodes/classes/YasrOverallRating.php](includes/shortcodes/classes/YasrOverallRating.php), [line 52](includes/shortcodes/classes/YasrOverallRating.php#L52-L52)

### `yasr_cstm_text_before_overall`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->overall_rating` |  | 

Source: [../includes/shortcodes/classes/YasrOverallRating.php](includes/shortcodes/classes/YasrOverallRating.php), [line 124](includes/shortcodes/classes/YasrOverallRating.php#L124-L124)

### `yasr_vv_cookie`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'yasr_visitor_vote_cookie'` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](includes/shortcodes/classes/YasrShortcodesAjax.php), [line 216](includes/shortcodes/classes/YasrShortcodesAjax.php#L216-L216)

### `yasr_vv_updated_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$rating_saved_text` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](includes/shortcodes/classes/YasrShortcodesAjax.php), [line 229](includes/shortcodes/classes/YasrShortcodesAjax.php#L229-L229)

### `yasr_vv_saved_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$rating_saved_text` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](includes/shortcodes/classes/YasrShortcodesAjax.php), [line 232](includes/shortcodes/classes/YasrShortcodesAjax.php#L232-L232)

### `yasr_vv_rating_error_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$error_text` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](includes/shortcodes/classes/YasrShortcodesAjax.php), [line 255](includes/shortcodes/classes/YasrShortcodesAjax.php#L255-L255)

### `yasr_mv_cookie`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'yasr_multi_visitor_cookie'` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](includes/shortcodes/classes/YasrShortcodesAjax.php), [line 502](includes/shortcodes/classes/YasrShortcodesAjax.php#L502-L502)

### `yasr_mv_saved_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`__('Rating Saved', 'yet-another-stars-rating')` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](includes/shortcodes/classes/YasrShortcodesAjax.php), [line 511](includes/shortcodes/classes/YasrShortcodesAjax.php#L511-L511)

### `yasr_filter_ranking_request`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`false` |  | 
`$request` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](includes/shortcodes/classes/YasrShortcodesAjax.php), [line 694](includes/shortcodes/classes/YasrShortcodesAjax.php#L694-L694)

### `yasr_add_sources_ranking_request`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$data_to_return` |  | 
`$source` |  | 
`$request` |  | 
`$sql_params` |  | 

Source: [../includes/shortcodes/classes/YasrShortcodesAjax.php](includes/shortcodes/classes/YasrShortcodesAjax.php), [line 745](includes/shortcodes/classes/YasrShortcodesAjax.php#L745-L745)

### `yasr_mv_cookie`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'yasr_multi_visitor_cookie'` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorMultiSet.php](includes/shortcodes/classes/YasrVisitorMultiSet.php), [line 115](includes/shortcodes/classes/YasrVisitorMultiSet.php#L115-L115)

### `yasr_must_sign_in`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`''` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorMultiSet.php](includes/shortcodes/classes/YasrVisitorMultiSet.php), [line 170](includes/shortcodes/classes/YasrVisitorMultiSet.php#L170-L170)

### `yasr_vv_ro_shortcode`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$shortcode_html` |  | 
`$stored_votes` |  | 
`$this->post_id` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](includes/shortcodes/classes/YasrVisitorVotes.php), [line 110](includes/shortcodes/classes/YasrVisitorVotes.php#L110-L110)

### `yasr_vv_cookie`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'yasr_visitor_vote_cookie'` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](includes/shortcodes/classes/YasrVisitorVotes.php), [line 120](includes/shortcodes/classes/YasrVisitorVotes.php#L120-L120)

### `yasr_cstm_text_already_voted`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$rating` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](includes/shortcodes/classes/YasrVisitorVotes.php), [line 194](includes/shortcodes/classes/YasrVisitorVotes.php#L194-L194)

### `yasr_must_sign_in`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`''` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](includes/shortcodes/classes/YasrVisitorVotes.php), [line 206](includes/shortcodes/classes/YasrVisitorVotes.php#L206-L206)

### `yasr_cstm_text_before_vv`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$number_of_votes` |  | 
`$average_rating` |  | 
`$this->unique_id` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](includes/shortcodes/classes/YasrVisitorVotes.php), [line 232](includes/shortcodes/classes/YasrVisitorVotes.php#L232-L232)

### `yasr_cstm_text_after_vv`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$number_of_votes` |  | 
`$average_rating` |  | 
`$this->unique_id` |  | 

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](includes/shortcodes/classes/YasrVisitorVotes.php), [line 275](includes/shortcodes/classes/YasrVisitorVotes.php#L275-L275)

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

Source: [../includes/shortcodes/classes/YasrVisitorVotes.php](includes/shortcodes/classes/YasrVisitorVotes.php), [line 378](includes/shortcodes/classes/YasrVisitorVotes.php#L378-L387)

### `yasr_tr_rankings_atts`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`false` |  | 
`$atts` |  | 

Source: [../includes/shortcodes/classes/YasrNoStarsRankings.php](includes/shortcodes/classes/YasrNoStarsRankings.php), [line 36](includes/shortcodes/classes/YasrNoStarsRankings.php#L36-L36)

### `yasr_tu_rankings_atts`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`false` |  | 
`$atts` |  | 

Source: [../includes/shortcodes/classes/YasrNoStarsRankings.php](includes/shortcodes/classes/YasrNoStarsRankings.php), [line 63](includes/shortcodes/classes/YasrNoStarsRankings.php#L63-L63)

### `yasr_tu_rankings_display`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$user_data->user_login` |  | 
`$user_data` |  | 

Source: [../includes/shortcodes/classes/YasrNoStarsRankings.php](includes/shortcodes/classes/YasrNoStarsRankings.php), [line 124](includes/shortcodes/classes/YasrNoStarsRankings.php#L124-L124)

### `yasr_size_ranking`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'medium'` |  | 

Source: [../includes/shortcodes/classes/YasrShortcode.php](includes/shortcodes/classes/YasrShortcode.php), [line 89](includes/shortcodes/classes/YasrShortcode.php#L89-L89)

### `yasr_ov_rankings_atts`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->shortcode_name` |  | 
`$atts` |  | 

Source: [../includes/shortcodes/classes/YasrRankings.php](includes/shortcodes/classes/YasrRankings.php), [line 55](includes/shortcodes/classes/YasrRankings.php#L55-L55)

### `yasr_vv_rankings_atts`

*Hook here to use shortcode atts.*

If not used, will works with no support for atts

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->shortcode_name` |  | 
`$atts` | `string\|array` | Shortcode atts

Source: [../includes/shortcodes/classes/YasrRankings.php](includes/shortcodes/classes/YasrRankings.php), [line 78](includes/shortcodes/classes/YasrRankings.php#L78-L85)

### `yasr_multi_set_ranking_atts`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->shortcode_name` |  | 
`$atts` |  | 

Source: [../includes/shortcodes/classes/YasrRankings.php](includes/shortcodes/classes/YasrRankings.php), [line 113](includes/shortcodes/classes/YasrRankings.php#L113-L113)

### `yasr_visitor_multi_set_ranking_atts`

*Hook here to use shortcode atts.*

If not used, shortcode will works only with setId param

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this->shortcode_name` |  | 
`$atts` | `string\|array` | Shortcode atts

Source: [../includes/shortcodes/classes/YasrRankings.php](includes/shortcodes/classes/YasrRankings.php), [line 138](includes/shortcodes/classes/YasrRankings.php#L138-L145)

### `yasr_filter_ip`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$ip` |  | 

Source: [../includes/yasr-includes-functions.php](includes/yasr-includes-functions.php), [line 148](includes/yasr-includes-functions.php#L148-L148)

### `yasr_rest_rankings_args`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$args` |  | 

Source: [../includes/rest/classes/YasrCustomEndpoint.php](includes/rest/classes/YasrCustomEndpoint.php), [line 146](includes/rest/classes/YasrCustomEndpoint.php#L146-L146)

### `yasr_rest_sanitize`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$key` |  | 
`$param` |  | 

Source: [../includes/rest/classes/YasrCustomEndpoint.php](includes/rest/classes/YasrCustomEndpoint.php), [line 277](includes/rest/classes/YasrCustomEndpoint.php#L277-L277)

### `yasr_rankings_query_ov`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$atts` |  | 

Source: [../includes/classes/YasrDB.php](includes/classes/YasrDB.php), [line 201](includes/classes/YasrDB.php#L201-L201)

### `yasr_rankings_query_vv`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$atts` |  | 
`$ranking` |  | 

Source: [../includes/classes/YasrDB.php](includes/classes/YasrDB.php), [line 245](includes/classes/YasrDB.php#L245-L245)

### `yasr_rankings_query_tu`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$atts` |  | 

Source: [../includes/classes/YasrDB.php](includes/classes/YasrDB.php), [line 295](includes/classes/YasrDB.php#L295-L295)

### `yasr_rankings_multi_query`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$sql_atts` |  | 
`$set_id` |  | 

Source: [../includes/classes/YasrDB.php](includes/classes/YasrDB.php), [line 340](includes/classes/YasrDB.php#L340-L340)

### `yasr_rankings_query_tr`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$atts` |  | 

Source: [../includes/classes/YasrDB.php](includes/classes/YasrDB.php), [line 394](includes/classes/YasrDB.php#L394-L394)

### `yasr_rankings_multivv_query`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$sql_atts` |  | 
`$ranking` |  | 
`$set_id` |  | 

Source: [../includes/classes/YasrDB.php](includes/classes/YasrDB.php), [line 445](includes/classes/YasrDB.php#L445-L445)

### `yasr_custom_loader`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$yasr_visitor_votes_loader` |  | 

Source: [../includes/classes/YasrScriptsLoader.php](includes/classes/YasrScriptsLoader.php), [line 59](includes/classes/YasrScriptsLoader.php#L59-L59)

### `yasr_custom_loader_url`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`YASR_IMG_DIR . 'loader.gif'` |  | 

Source: [../includes/classes/YasrScriptsLoader.php](includes/classes/YasrScriptsLoader.php), [line 63](includes/classes/YasrScriptsLoader.php#L63-L63)

### `yasr_gutenberg_constants`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$constants_array` |  | 

Source: [../includes/classes/YasrScriptsLoader.php](includes/classes/YasrScriptsLoader.php), [line 525](includes/classes/YasrScriptsLoader.php#L525-L525)


<p align="center"><a href="https://github.com/pronamic/wp-documentor"><img src="https://cdn.jsdelivr.net/gh/pronamic/wp-documentor@main/logos/pronamic-wp-documentor.svgo-min.svg" alt="Pronamic WordPress Documentor" width="32" height="32"></a><br><em>Generated by <a href="https://github.com/pronamic/wp-documentor">Pronamic WordPress Documentor</a> <code>1.2.0</code></em><p>

