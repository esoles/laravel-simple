const API_BASE_URL = 'http://localhost:8001/api';

function displayMessage(messageBoxElement, message, type, errors = null) {
    messageBoxElement.textContent = '';
    messageBoxElement.classList.remove('success', 'error');
    messageBoxElement.style.display = 'block';

    let content = `<p>${message}</p>`;

    if (errors) {
        content += '<ul>';
        for (const key in errors) {
            if (Object.hasOwnProperty.call(errors, key)) {
                errors[key].forEach(error => {
                    content += `<li>- ${error}</li>`;
                });
            }
        }
        content += '</ul>';
    }
    messageBoxElement.innerHTML = content;
    messageBoxElement.classList.add(type);
}

function clearMessage(messageBoxElement) {
    messageBoxElement.textContent = '';
    messageBoxElement.style.display = 'none';
    messageBoxElement.classList.remove('success', 'error');
}

async function loadCategories() {
    const categoriesContainer = document.getElementById('categories-container');
    const serviceCategorySelect = document.getElementById('service-category');

    categoriesContainer.innerHTML = '<p class="flex items-center"><img src="/frontend/loading.gif" width="30" /> &nbsp;Cargando categorías</p>';
    serviceCategorySelect.innerHTML = '<option value="">Cargando categorías...</option>';

    try {
        const response = await fetch(`${API_BASE_URL}/categories`);
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
        }
        const responseData = await response.json();
        const categories = responseData.data;

        categoriesContainer.innerHTML = '';

        serviceCategorySelect.innerHTML = '<option value="">Seleccione categoría</option>';
        if (categories && Array.isArray(categories) && categories.length > 0) {
            const ul = document.createElement('ul');
            categories.forEach(ca => {
                const li = document.createElement('li');
                li.classList.add('list-item');
                li.innerHTML = `
                    <div class="grid grid-cols-5">
                        <div class="text-gray-800"><strong>${ca.name}</strong></div>
                        <div class="text-gray-600">${ca.description || ''}</div>
                        <div class="text-gray-600 text-center">${ca.position || ''}</div>
                        ${ca.status === 'enabled' ? '<span class="h-5 w-5 rounded-full bg-green-500"></span>' : '<span class="h-5 w-5 rounded-full bg-red-500"></span>'}
                        <div class="actions flex justify-center">
                            <button class="edit-btn" data-id="${ca.id}" data-name="${ca.name}" data-description="${ca.description || ''}"  data-position="${ca.position || ''}" data-status="${ca.status}">Editar</button>
                            <button class="delete-btn" data-id="${ca.id}">Eliminar</button>
                        </div>    
                    </div>  
                `;

                ul.appendChild(li);

                const option = document.createElement('option');
                option.value = ca.id;
                option.textContent = ca.name;
                serviceCategorySelect.appendChild(option);
            });
            categoriesContainer.appendChild(ul);

            document.querySelectorAll('#categories-container .edit-btn').forEach(button => {
                button.addEventListener('click', editCategory);
            });
            document.querySelectorAll('#categories-container .delete-btn').forEach(button => {
                button.addEventListener('click', deleteCategory);
            });

        } else {
            categoriesContainer.innerHTML = '<p>No se encontraron categorías.</p>';
        }

    } catch (error) {
        console.error('Hubo un problema al obtener las categorías:', error);
        categoriesContainer.innerHTML = `<p style="color: red;">Error al cargar las categorías: ${error.message}</p>`;
        serviceCategorySelect.innerHTML = '<option value="">Error al cargar categorías</option>';
    }
}

async function registerCategory(event) {
    event.preventDefault();

    const form = event.target;
    const categoryId = document.getElementById('category-id').value;
    const name = form['category-name'].value;
    const description = form['category-description'].value;
    const position = form['category-position'].value;
    const status = form['category-status'].value;
    const messageBox = document.getElementById('category-message');

    clearMessage(messageBox);

    const method = categoryId ? 'PUT' : 'POST';
    const url = categoryId ? `${API_BASE_URL}/categories/${categoryId}` : `${API_BASE_URL}/categories`;

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name, description, position, status })
        });

        const responseData = await response.json();
        if (response.ok) {
            displayMessage(messageBox, responseData.message || `Categoría ${categoryId ? 'actualizada' : 'registrada'} con éxito.`, 'success');
            form.reset();
            document.getElementById('category-id').value = '';
            document.getElementById('category-submit-btn').textContent = 'Registrar Categoría';
            document.getElementById('category-cancel-btn').style.display = 'none';
            loadCategories();
        } else {
            let errorMessage = responseData.message || `Error al ${categoryId ? 'actualizar' : 'registrar'} la categoría.`;
            displayMessage(messageBox, errorMessage, 'error', responseData.errors);
        }
    } catch (error) {
        console.error('Error en la operación de categoría:', error);
        displayMessage(messageBox, `Error de conexión: ${error.message}`, 'error');
    }
}

async function editCategory(event) {
    const button = event.target;
    const id = button.dataset.id;
    const name = button.dataset.name;
    const description = button.dataset.description;
    const position = button.dataset.position;
    const status = button.dataset.status;

    document.getElementById('category-id').value = id;
    document.getElementById('category-name').value = name;
    document.getElementById('category-description').value = description;
    document.getElementById('category-position').value = position;
    document.getElementById('category-status').value = status;
    document.getElementById('category-submit-btn').textContent = 'Actualizar categoria';
    document.getElementById('category-cancel-btn').style.display = 'inline-block';
    clearMessage(getElementById('category-message'));
}

async function deleteCategory(event) {
    const categoryId = event.target.dataset.id;
    const response = await fetch(`${API_BASE_URL}/categories/${categoryId}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json' }

    });
    const messageBox = document.getElementById('categories-container');
    if (response.ok) {
        displayMessage(messageBox, 'categoria eliminada');
        loadCategories();
    }
}

function cancelCategoryEdit() {
    document.getElementById('category-form').reset();
    document.getElementById('category-id').value = '';
    document.getElementById('category-submit-btn').textContent = 'Registrar Categoría';
    document.getElementById('category-cancel-btn').style.display = 'none';
    clearMessage(document.getElementById('category-message'));
}

async function loadServices() {
    const servicesContainer = document.getElementById('services-container');
    servicesContainer.innerHTML = '<p class="flex items-center"><img src="/frontend/loading.gif" width="30" /> &nbsp;Cargando servicios</p>';

    try {
        const response = await fetch(`${API_BASE_URL}/services`);
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
        }
        const responseData = await response.json();
        const services = responseData.data;

        servicesContainer.innerHTML = '';

        if (services && Array.isArray(services) && services.length > 0) {
            const ul = document.createElement('ul');
            services.forEach(service => {
                const li = document.createElement('li');
                li.classList.add('list-item');
                li.innerHTML = `
                    <div class="grid grid-cols-7">
                        <div class="text-gray-800">${service.name}</div>
                        <div class="text-gray-600">${service.description || ''}</div>
                        <div class="text-gray-600 text-center">S/${service.price}</div>
                        <div class="text-gray-600 text-center">${service.duration || ''}</div>
                        <div class="text-gray-600 text-center">${service.position}</div>
                        ${service.status === 'enabled' ? '<span class="h-5 w-5 rounded-full bg-green-500"></span>' : '<span class="h-5 w-5 rounded-full bg-red-500"></span>'}
                        <div class="actions flex justify-center">
                            <button class="edit-btn self-start" data-id="${service.id}" data-name="${service.name}" data-description="${service.description || ''}" data-price="${service.price}" data-category-id="${service.category_id || ''}" data-position="${service.position}" data-duration="${service.duration || ''}" data-status="${service.status}">Editar</button>
                            <button class="delete-btn self-start" data-id="${service.id}">Eliminar</button>
                        </div>
                    </div>
                `;
                ul.appendChild(li);
            });
            servicesContainer.appendChild(ul);

            document.querySelectorAll('#services-container .edit-btn').forEach(button => {
                button.addEventListener('click', editService);
            });
            document.querySelectorAll('#services-container .delete-btn').forEach(button => {
                button.addEventListener('click', deleteService);
            });

        } else {
            servicesContainer.innerHTML = '<p>No se encontraron servicios.</p>';
        }

    } catch (error) {
        console.error('Hubo un problema al obtener los servicios:', error);
        servicesContainer.innerHTML = `<p style="color: red;">Error al cargar los servicios: ${error.message}</p>`;
    }
}

async function registerService(event) {
    event.preventDefault();

    const form = event.target;
    const serviceId = document.getElementById('service-id').value;
    const category_id = parseInt(form['service-category'].value);
    const name = form['service-name'].value;
    const description = form['service-description'].value;
    const price = parseFloat(form['service-price'].value);
    const position = parseFloat(form['service-position'].value);
    const duration = form['service-duration'].value;
    const status = form['service-status'].value;
    const messageBox = document.getElementById('service-message');

    clearMessage(messageBox);

    const method = serviceId ? 'PUT' : 'POST';
    const url = serviceId ? `${API_BASE_URL}/services/${serviceId}` : `${API_BASE_URL}/services`;

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ category_id, name, description, price, position, duration, status })
        });

        const responseData = await response.json();
        if (response.ok) {
            displayMessage(messageBox, responseData.message || `servicio ${serviceId ? 'actualizado' : 'registrado'} con éxito.`, 'success');
            form.reset();
            document.getElementById('service-id').value = '';
            document.getElementById('service-submit-btn').textContent = 'Registrar servicio';
            document.getElementById('service-cancel-btn').style.display = 'none';
            loadServices();
        } else {
            let errorMessage = responseData.message || `Error al ${serviceId ? 'actualizar' : 'registrar'} el servicio.`;
            displayMessage(messageBox, errorMessage, 'error', responseData.errors);
        }
    } catch (error) {
        console.error('Error en la operación de servicio:', error);
        displayMessage(messageBox, `Error de conexión: ${error.message}`, 'error');
    }
}

async function editService(event) {
    const button = event.target;
    const id = button.dataset.id;
    const categoryId = button.dataset.categoryId;
    const name = button.dataset.name;
    const description = button.dataset.description;
    const price = button.dataset.price;
    const duration = button.dataset.duration;
    const position = button.dataset.position;
    const status = button.dataset.status;

    document.getElementById('service-id').value = id;
    document.getElementById('service-category').value = categoryId;
    document.getElementById('service-name').value = name;
    document.getElementById('service-description').value = description;
    document.getElementById('service-price').value = price;
    document.getElementById('service-duration').value = duration;
    document.getElementById('service-position').value = position;
    document.getElementById('service-status').value = status;
    document.getElementById('service-submit-btn').textContent = 'Actualizar servicio';
    document.getElementById('service-cancel-btn').style.display = 'inline-block';
    clearMessage(document.getElementById('service-message'));
}

async function deleteService(event) {
    const serviceId = event.target.dataset.id;
    const response = await fetch(`${API_BASE_URL}/services/${serviceId}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json' }
    });
    const messageBox = document.getElementById('services-container');
    if (response.ok) {
        displayMessage(messageBox, 'servicio eliminado');
        loadServices();
    }
}

function cancelServiceEdit() {
    document.getElementById('service-form').reset();
    document.getElementById('service-id').value = '';
    document.getElementById('service-submit-btn').textContent = 'Registrar servicio';
    document.getElementById('service-cancel-btn').style.display = 'none';
    clearMessage(document.getElementById('service-message'));
}

document.addEventListener('DOMContentLoaded', () => {
    loadCategories();
    loadServices();

    document.getElementById('category-form').addEventListener('submit', registerCategory);
    document.getElementById('category-cancel-btn').addEventListener('click', cancelCategoryEdit);

    document.getElementById('service-form').addEventListener('submit', registerService);
    document.getElementById('service-cancel-btn').addEventListener('click', cancelServiceEdit);
});