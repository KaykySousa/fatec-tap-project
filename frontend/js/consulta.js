const form = document.getElementById("form")
const deleteButton = document.getElementById("delete-button")
const updateButton = document.getElementById("update-button")

const nameInput = document.getElementById("name-input")
const emailInput = document.getElementById("email-input")
const birthdateInput = document.getElementById("birthdate-input")

const nextButton = document.getElementById("next-button")
const prevButton = document.getElementById("prev-button")
const userIndexSpan = document.getElementById("user-index")

const loading = document.getElementById("loading")

form.addEventListener("submit", updateUser)
deleteButton.addEventListener("click", deleteUser)

nextButton.addEventListener("click", () => {
	userIndex++
	renderUser()
})
prevButton.addEventListener("click", () => {
	userIndex--
	renderUser()
})

let users = []
let userIndex = 0

async function fetchUsers() {
	nameInput.disabled = true
	emailInput.disabled = true
	birthdateInput.disabled = true
	deleteButton.disabled = true
	updateButton.disabled = true
	prevButton.disabled = true
	nextButton.disabled = true

	try {
		const res = await fetch(`${API_URL}/users`)
		if (!res.ok) throw new Error("Fetch error")
		users = await res.json()
		renderUser()
	} catch (error) {
		console.error(error)
		alert("Erro ao buscar usuários!")
	}
}

async function updateUser(e) {
	e.preventDefault()

	const formData = new FormData(form)

	const id = users[userIndex]?.id

	if (!id) return

	const data = {
		name: formData.get("name"),
		email: formData.get("email"),
		birthDate: formData.get("birthdate"),
	}

	try {
		const res = await fetch(`${API_URL}/users/${id}`, {
			method: "PUT",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(data),
		})
		if (!res.ok) throw new Error("Fetch error")

		alert("Usuário atualizado com sucesso!")
		form.reset()
		fetchUsers()
	} catch (error) {
		console.error(error)
		alert("Erro ao atualizar usuário!")
	}
}

async function deleteUser() {
	const id = users[userIndex]?.id
	if (!id) return

	if (!confirm("Deseja realmente excluir este usuário?")) return

	try {
		const res = await fetch(`${API_URL}/users/${id}`, {
			method: "DELETE",
		})
		if (!res.ok) throw new Error("Fetch error")

		alert("Usuário deletado com sucesso!")
		form.reset()
		fetchUsers()
	} catch (error) {
		console.error(error)
		alert("Erro ao deletar usuário!")
	}
}

function renderUser() {
	loading.classList.add("hidden")
	userIndexSpan.classList.remove("hidden")

	if (users.length === 0) {
		alert("Nenhum usuário cadastrado!")
		return
	}

	nameInput.disabled = false
	emailInput.disabled = false
	birthdateInput.disabled = false
	deleteButton.disabled = false
	updateButton.disabled = false
	prevButton.disabled = false
	nextButton.disabled = false

	if (userIndex <= 0) {
		prevButton.disabled = true
		userIndex = 0
	}
	if (userIndex >= users.length - 1) {
		nextButton.disabled = true
		userIndex = users.length - 1
	}

	userIndexSpan.textContent = userIndex + 1

	const user = users[userIndex]

	if (!user) return

	nameInput.value = user.name
	emailInput.value = user.email
	birthdateInput.value = user.birthDate.split("/").reverse().join("-")
}

fetchUsers()
