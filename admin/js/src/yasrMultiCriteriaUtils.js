/**
 * Handle the "Add new Criteria" button
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
                    data-id-criteria="multi-set-name-element-${nCriteria}"
                    >            
                </span>`;

        document.getElementById('yasr-multiset-page-new-set-criteria-container').appendChild(newCriteria);

        newElementButton.value = nCriteria + 1;
    }
}

export const removeMultisetCriteria = (deleteButtons) => {

    for (let i = 0; i < deleteButtons.length; i++) {
        (function (i) {

            deleteButtons[i].onclick = (event) => {
                let idDivToRemove = deleteButtons[i].dataset.idCriteria;
                console.log('click on delete ' + idDivToRemove);
            }

            /*deleteButtons[i].onclick = (event) => {
                console.log(deleteButtons);
                let idDivToRemove = deleteButtons[i].dataset.idCriteria;
                document.getElementById(idDivToRemove).remove()
            }*/

        })(i);
    }//End for
}