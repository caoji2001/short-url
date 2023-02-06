const deleteModal = document.getElementById('deleteModal')
const modifyModal = document.getElementById('modifyModal')

modifyModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget
    const siteurl = window.location.origin + "/"
    const id62 = button.getAttribute('data-bs-id62')
    const url = button.getAttribute('data-bs-url')

    document.getElementById('mod_get_siteurl').innerText = siteurl
    document.getElementById('mod_get_id62').value = id62
    document.getElementById('mod_get_url').value = url
})

deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget
    const siteurl = window.location.origin + "/"
    const id62 = button.getAttribute('data-bs-id62')
    const url = button.getAttribute('data-bs-url')

    document.getElementById('del_get_siteurl').innerText = siteurl
    document.getElementById('del_get_id62').value = id62
    document.getElementById('del_get_url').value = url
})