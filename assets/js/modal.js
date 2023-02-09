const modifyModal = document.getElementById('modifyModal')
const deleteModal = document.getElementById('deleteModal')

modifyModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget
    const siteurl = window.location.origin + "/"
    const id62 = button.parentNode.parentNode.children[0].innerHTML
    const url = button.parentNode.parentNode.children[1].innerHTML

    document.getElementById('mod_get_siteurl').innerText = siteurl
    document.getElementById('mod_get_id62').value = id62
    document.getElementById('mod_get_url').value = url
})

deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget
    const siteurl = window.location.origin + "/"
    const id62 = button.parentNode.parentNode.children[0].innerHTML
    const url = button.parentNode.parentNode.children[1].innerHTML

    document.getElementById('del_get_siteurl').innerText = siteurl
    document.getElementById('del_get_id62').value = id62
    document.getElementById('del_get_url').value = url
})