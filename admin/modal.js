const deleteModal = document.getElementById('deleteModal')
const formSubmit = document.getElementById('form_submit')

deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget
    const siteurl = button.getAttribute('data-bs-siteurl')
    const id62 = button.getAttribute('data-bs-id62')
    const url = button.getAttribute('data-bs-url')

    document.getElementById('get_siteurl').innerText = siteurl
    document.getElementById('get_id62').value = id62
    document.getElementById('get_url').value = url
})