import datepicker from 'js-datepicker';

wp.hooks.addAction( 'yasr_ranking_page_top', 'yasr', function(){
    //Start date, doc https://github.com/qodesmith/datepicker
    const startDate = datepicker('#yasr-builder-datepicker-start', {
        id: 1,
        //Format the date to yyyy-mm-dd
        formatter: (input, date, instance) => {
            input.value = new Date(date.getTime() - (date.getTimezoneOffset() * 60000))
                .toISOString()
                .split("T")[0]; // split after the T to remove hh:mm:ss
        },
        //When the date is selected, the event listener in yasr-settings-rankings doesn't catch it as a change.
        //So, I need to create a new event
        onSelect: (instance, date) => {
            let element = instance.el;
            element.dispatchEvent(new Event('change', {'bubbles': true })); // or whatever the event type might be
        }
    });

    //End date, doc https://github.com/qodesmith/datepicker
    const endDate   = datepicker('#yasr-builder-datepicker-end', {
        id: 1,
        //Format the date to yyyy-mm-dd
        formatter: (input, date, instance) => {
            input.value = new Date(date.getTime() - (date.getTimezoneOffset() * 60000))
                .toISOString()
                .split("T")[0]; // => '2021-11-17'
        },
        //When the date is selected, the event listener in yasr-settings-rankings doesn't catch it as a change.
        //So, I need to create a new event
        onSelect: (instance, date) => {
            let element = instance.el;
            element.dispatchEvent(new Event('change', { 'bubbles': true })); // or whatever the event type might be
        }
    });

    wp.hooks.addFilter('yasrBuilderFilterShortcode', 'yet-another-stars-rating', yasrFilterShortcodeCallback, 10);

    function yasrFilterShortcodeCallback(shortcodeAttribute) {

        if(event.target.id === 'yasr-builder-rows') {
            shortcodeAttribute['rows'] =  ' rows='+event.target.value;
        }

        //size is radio group  so it must be with the class
        if(event.target.matches('.yasr-builder-size')){
            shortcodeAttribute['size'] = ' size='+event.target.value;
        }

        //size is radio group  so it must be with the class
        if(event.target.matches('.yasr-vv-default-view')){
            shortcodeAttribute['view'] = ' view='+event.target.value;
        }

        if(event.target.id === 'yasr-required-votes-most') {
            shortcodeAttribute['minvotesmost'] =  '  minvotesmost='+event.target.value;
        }

        if(event.target.id === 'yasr-required-votes-highest') {
            shortcodeAttribute['minvoteshg'] =  '  minvoteshg='+event.target.value;
        }

        if(event.target.matches('.yasr-builder-custom-text-overall')){
            let value = event.target.value;

            //the input text element
            const inputTextOv = document.getElementById('yasr-builder-customize-ov-text');

            if(value !== 'no') {
                //enable text input
                inputTextOv.disabled = false;
                shortcodeAttribute['txtPosition'] = ' text_position=' + value;
                shortcodeAttribute['txt']         = ' text=' + inputTextOv.value;
            } else {
                //disable text input
                inputTextOv.disabled = true;
                shortcodeAttribute['txtPosition'] = '';
                shortcodeAttribute['txt']         = '';
            }
        }

        if(event.target.matches('#yasr-builder-customize-ov-text')){
            let customText            = event.target.value;
            shortcodeAttribute['txt'] = ' text=' + customText;
        }

        //size is radio group  so it must be with the class
        if(event.target.matches('.yasr-builder-user-option')) {
            shortcodeAttribute['display'] = ' display='+event.target.value;
        }

        //size is radio group  so it must be with the class
        if(event.target.matches('#yasr-builder-datepicker-start')) {
            if(event.target.value !== '') {
                shortcodeAttribute['start_date'] = ' start_date='+event.target.value;
            } else {
                shortcodeAttribute['start_date'] = '';
            }
        }

        //size is radio group  so it must be with the class
        if(event.target.matches('#yasr-builder-datepicker-end')) {
            if(event.target.value !== '') {
                shortcodeAttribute['end_date'] = ' end_date=' + event.target.value;
            }
            else {
                shortcodeAttribute['end_date'] = '';
            }
        }

        return shortcodeAttribute;
    }

});