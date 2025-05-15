$(function() {
    $(document).ready(function() {
        function functionAnimation() {
            const selector = document.querySelector('.imageTemp')
            // console.log(selector.className.split(/\s+/));
            var classAni = selector.className.split(/\s+/);
            if (classAni[1]=="magictime"&&classAni[2]=="swashIn") {
                selector.classList.remove('magictime', 'swashIn');
                selector.classList.add('magictime', 'holeOut');
            }else{
                selector.classList.remove('magictime', 'holeOut')
                selector.classList.add('magictime', 'swashIn')
            }
        }
        function deleteAnimation() {
            const selector = document.querySelector('.imageTemp')
            
            
        }
        setInterval(functionAnimation, 1000);
    });
});
function showAnimated(){
    const selfLoading = document.querySelector('#selfLoading');
    selfLoading.classList.remove('hide')
}

function hideAnimated(){
    const selfLoading = document.querySelector('#selfLoading');
    selfLoading.classList.add('hide')
}