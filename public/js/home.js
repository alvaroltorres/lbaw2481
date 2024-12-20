document.addEventListener('DOMContentLoaded', () => {
    const btn = document.querySelector('.btn-explore');

    btn.addEventListener('mouseover', () => {
        btn.style.backgroundColor = '#ff856e';
    });

    btn.addEventListener('mouseout', () => {
        btn.style.backgroundColor = '#ff6f61';
    });
});
