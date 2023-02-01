const deleteModal = document.getElementById('deleteModal')

deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget
    const data10 = button.getAttribute('data-bs-id10')
    const url = button.getAttribute('data-bs-url')

    document.getElementById('get_url').innerHTML = url;
    document.getElementById('delete_op_link').setAttribute('href', `./del.php?id=${data10}`);
})