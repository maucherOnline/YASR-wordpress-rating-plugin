/**
 * Handle the "Add new Criteria" button
 *
 * @param newElementButton
 */
export const addMultisetCriteria = (newElementButton) => {
    newElementButton.onclick = (event) => {
        event.preventDefault();

        let  nCriteria   = parseInt(newElementButton.value);

        //Row number must be >= 3 and < 9
        if(nCriteria < 3 || nCriteria > 9 ) {
            return;
        }

        //Create the div
        const newCriteria = document.createElement('div');

        newCriteria.id        = `criteria-row-container-${nCriteria}`;
        newCriteria.className = `criteria-row`;

        newCriteria.innerHTML = `
                <label for="multi-set-name-element-${nCriteria}">
                     <span class="yasr-sort-criteria dashicons dashicons-menu"></span>
                </label>
                <input type="text"
                    name="multi-set-name-element-${nCriteria}"
                    id="multi-set-name-element-${nCriteria}"
                    class="input-text-multi-set"
                    placeholder="New Criteria"
                />
                <span 
                    class="dashicons dashicons-remove yasr-multiset-info-delete criteria-delete" 
                    id="remove-criteria-${nCriteria}"
                    data-id-criteria="${newCriteria.id}"
                    >            
                </span>`;

        document.getElementById('yasr-multiset-page-new-set-criteria-container').appendChild(newCriteria);

        //update the value of the button
        newElementButton.value = nCriteria + 1;

        //add new event onClick on new button delete
        removeMultisetCriteria(nCriteria);
    }
}

/**
 * Manage the click on buttonDelete
 *
 * @param startFor | 3 At page load, first button delete start on row 3
 */
export const removeMultisetCriteria = (startFor = 3) => {

    const newElementButton = document.getElementById('new-criteria-button');

    //Number of existing rows
    const nOfCriteria = parseInt(newElementButton.value) - 1;

    //add an onclick event for every delete button
    for (let i = startFor; i <= nOfCriteria; i++) {
        const buttonDelete = document.getElementById(`remove-criteria-${i}`);

        buttonDelete.onclick = (event) => {
            let idDivToRemove = buttonDelete.dataset.idCriteria;
            document.getElementById(idDivToRemove).remove();

            //update the value of the button
            newElementButton.value = newElementButton.value - 1;
        }

    }//End for

    //call this again or "Add new criteria will not work after "
    addMultisetCriteria(newElementButton);
}