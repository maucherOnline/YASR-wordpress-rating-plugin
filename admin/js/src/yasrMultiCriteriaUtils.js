/**
 *
 *
 * @param newElementButton
 */
export const addMultisetCriteria = (newElementButton) => {
    newElementButton.onclick = (event) => {
        event.preventDefault();

        let   nCriteria   = parseInt(newElementButton.value);

        //Row number must be >= 5 and < 9
        if(nCriteria < 5 || nCriteria > 9 ) {
            return;
        }

        //Create the div
        const newCriteria = document.createElement('div');

        newCriteria.innerHTML = `
            <div class="yasr-multiset-page-new-set-criteria-row">
                <label for="multi-set-name-element-${nCriteria}">
                     <span class="yasr-sort-criteria dashicons dashicons-menu"></span>
                </label>
                <input type="text"
                    name="multi-set-name-element-${nCriteria}"
                    id="multi-set-name-element-${nCriteria}"
                    class="input-text-multi-set"
                    placeholder="New Criteria"'
                />
                <span 
                    class="dashicons dashicons-remove yasr-multiset-info-delete" 
                    id="remove-criteria-${nCriteria}"
                    data-id-criteria="multi-set-name-element-${nCriteria}"
                    >            
                </span>
            </div>`;

        document.getElementById('yasr-multiset-page-new-set-criteria-container').appendChild(newCriteria);

        newElementButton.value = nCriteria + 1;
    }
}