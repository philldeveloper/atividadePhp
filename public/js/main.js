
const type_operation = document.querySelector('#transaction_operation');
const target_account = document.querySelector('#transaction_targetAccount');
const label = document.querySelector('.target-label');

if(type_operation){
    if(type_operation.value == 1){
        target_account.style.display = 'none';
        label.style.display = 'none';
    }
    
    if(type_operation.value == 2){
        target_account.style.display = 'none';
        label.style.display = 'none';
    }
}

function findTransaction(){
    if(type_operation){

        if(type_operation.value == 1){
            target_account.style.display = 'none';
            label.style.display = 'none';
        }
        
        if(type_operation.value == 2){
            target_account.style.display = 'none';
            label.style.display = 'none';
        }
            
        if(type_operation.value == 3){
            target_account.style.display = 'block';
            label.style.display = 'block';
        }
    }
        
}