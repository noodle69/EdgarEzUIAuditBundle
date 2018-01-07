(function () {
    const iconsAuditExportUp = document.querySelectorAll('a.audit-export-up');
    const iconAuditExportDown = document.querySelectorAll('a.audit-export-down');

    const exportUp = (event) => {
        event.preventDefault();
        const svgUp = event.currentTarget.querySelector('.ez-icon-caret-up');
        const svgDown = event.currentTarget.querySelector('.ez-icon-caret-down');
        svgDown.style.display = "none";
        svgUp.style.display = "block";
        event.currentTarget.parentNode.parentNode.nextElementSibling.style.display = "table-row";
        event.currentTarget.removeEventListener('click', exportUp);
        event.currentTarget.addEventListener('click', exportDown, false)
    };

    const exportDown = (event) => {
        event.preventDefault();
        const svgUp = event.currentTarget.querySelector('.ez-icon-caret-up');
        const svgDown = event.currentTarget.querySelector('.ez-icon-caret-down');
        svgDown.style.display = "block";
        svgUp.style.display = "none";
        event.currentTarget.parentNode.parentNode.nextElementSibling.style.display = "none";
        event.currentTarget.removeEventListener('click', exportDown);
        event.currentTarget.addEventListener('click', exportUp, false)
    };

    iconsAuditExportUp.forEach(icon => icon.addEventListener('click', exportUp, false));
    iconAuditExportDown.forEach(icon => icon.addEventListener('click', exportDown, false));
})();
