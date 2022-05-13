wp.hooks.addFilter('yasrBuilderFilterShortcode', 'yet-another-stars-rating', yasrFilterShortcodeCtgCpt, 11);

function yasrFilterShortcodeCtgCpt(shortcodeAttribute) {
    let cptContainer      = '.yasr-builder-custom-post-radio'; //cpt container
    let categoryContainer = '.yasr-builder-category'; //category container

    const divCpt           = document.getElementById('builder-cpt');

    //This will work when radio category are selected
    if (event.target.matches('.yasr-builder-enable-category')) {
        let categoryContainer = '.yasr-builder-category';  //category container
        let el = document.getElementById(event.target.id);

        if (el.value === '') {
            jQuery(categoryContainer).prop('disabled', true);
            shortcodeAttribute['category'] = '';
        } else {
            jQuery(categoryContainer).prop('disabled', false);
            if(divCpt !== null) {
                jQuery(cptContainer).prop('disabled', true);
                shortcodeAttribute['cpt'] = '';
                document.getElementById('yasr-builder-enable-cpt-radio-0').checked = true; //set radio cpt to "no"
            }
        }
    }

    if (event.target.matches('.yasr-builder-category')) {
        let el = document.getElementById(event.target.id);
        if (el.checked) {
            //add 'category' text if is empty
            if (shortcodeAttribute['category'] === '') {
                //add the category if only if doesn't exists yet in the string
                shortcodeAttribute['category'] = ' category="' + event.target.value+'"';
            } else {
                //if the category doesn't exists yet in the string
                if (shortcodeAttribute['category'].includes(event.target.value) === false) {
                    //this must be always true, string must always end with "
                    if (shortcodeAttribute['category'].slice(-1) === '"') {
                        //replace " with ,
                        shortcodeAttribute['category'] = shortcodeAttribute['category'].replace(/.$/,", ")
                        //add the category and " at the end
                        shortcodeAttribute['category'] += event.target.value + '"';
                    }
                }
            }
        }
        //action to do when element is unchecked
        else {
            //if the selected category already exists in shortcodeAttribute['category']
            //remove it
            if (shortcodeAttribute['category'].includes(event.target.value)) {
                //remove the " at the end of the string
                shortcodeAttribute['category'] = shortcodeAttribute['category'].replace(/.$/,"")
                //this is needed when is last element if multiple categories are checked
                shortcodeAttribute['category'] = shortcodeAttribute['category'].replace(', ' + event.target.value, '');
                //this is needed when is the first element
                shortcodeAttribute['category'] = shortcodeAttribute['category'].replace(event.target.value +', ', '');
                //this is needed when is last and only element
                shortcodeAttribute['category'] = shortcodeAttribute['category'].replace(event.target.value, '');

                if(shortcodeAttribute['category'] !== '') {
                    shortcodeAttribute['category'] += '"';
                }
            }

            //use regexp to find a number into category string
            let ctgFound = shortcodeAttribute['category'].match(/\d+/);

            //if is not found means no category is selected and I've to clean the string
            if (ctgFound === null) {
                shortcodeAttribute['category'] = '';
            }

        }

    }

    //This will work when radio cpt are selected
    if (event.target.matches('.yasr-builder-enable-cpt')) {
        let el = document.getElementById(event.target.id);

        if (el.value === '') {
            jQuery(cptContainer).prop('disabled', true); //if no is selected, disable
            shortcodeAttribute['cpt'] = '';      //empty the string
        } else {
            jQuery(cptContainer).prop('disabled', false);
            jQuery(categoryContainer).prop('disabled', true); //disable category checkbox
            shortcodeAttribute['category'] = '';              //empty category string
            document.getElementById('yasr-builder-rankings-category-0').checked = true; //set radio category to "no"

            //select always the first cpt
            let firstCpt = document.getElementById('yasr-builder-custom-post-radio[0]');
            firstCpt.checked = true;
            shortcodeAttribute['cpt'] = ' custom_post='+firstCpt.value;
        }
    }

    if (event.target.matches('.yasr-builder-custom-post-radio')) {
        shortcodeAttribute['cpt'] = ' custom_post='+event.target.value;
    }

    return shortcodeAttribute;

}

//filter category inputs https://www.w3schools.com/howto/howto_js_filter_lists.asp
document.addEventListener('keyup', event =>{
    //the input text
    if(event.target.matches('.yasr-builder-category')){
        //get input text element
        let el = document.getElementById(event.target.id);

        //the container category container div
        let divToFilter = '';

        divToFilter = 'yasr-ranking-ctg-container';

        if (divToFilter !== '') {
            let categoryName, filter, i;
            let input = el;

            //get container of checkbox
            let divContainer = document.getElementById(divToFilter);
            //get all spans from the div container, that contains the checkbox
            let spans = divContainer.getElementsByTagName('span');
            //this is need to make it work also case unsensitive
            filter = input.value.toUpperCase();

            for (i = 0; i < spans.length; i++) {
                //get all tag <input>
                input = spans[i].getElementsByTagName("input")[0];
                //get category name (from data attribute)
                categoryName = input.getAttribute('data-category-name');
                //make category name uppercase (to make it match with filter)
                //and then use .indexOf, that returns -1 if the value to search for never occurs.
                if (categoryName.toUpperCase().indexOf(filter) === -1) {
                    //hide if indexof returns -1
                    spans[i].style.display = "none";
                } else {
                    //show it again otherwise
                    spans[i].style.display = "";
                }
            }
        }
    }

});