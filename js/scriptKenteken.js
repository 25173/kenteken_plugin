const submit = document.getElementById('submit_kenteken');
const kenteken = document.getElementById('kenteken');


submit.addEventListener('click',searchKenteken);
function searchKenteken() {
    let license = kenteken.value.replace('-' ,  '');
    window.location = 'https://autorapport.finnik.nl/kenteken/' + license.toLowerCase();
    // console.log('https://autorapport.finnik.nl/kenteken/' + license.toLowerCase())
}


