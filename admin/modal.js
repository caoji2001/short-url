const deleteModal = document.getElementById('deleteModal')

deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget
    const data10 = button.getAttribute('data-bs-id10')
    const data62 = button.getAttribute('data-bs-id62')

    document.getElementById('get_data62').innerHTML = data62;
    document.getElementById('delete_op_link').setAttribute('href', `./del.php?id=${data10}`);
})