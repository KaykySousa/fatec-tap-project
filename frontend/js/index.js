const form = document.getElementById("form")

form.addEventListener("submit", createUser)

async function createUser(e) {
	e.preventDefault()

	const formData = new FormData(form)

	const data = {
		name: formData.get("name"),
		email: formData.get("email"),
		birthDate: formData.get("birthdate"),
	}

	try {
		const res = await fetch(`${API_URL}/users`, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(data),
		})
		if (!res.ok) throw new Error("Fetch error")

		alert("Usuário cadastrado com sucesso!")
	} catch (error) {
		console.error(error)
		alert("Erro ao cadastrar usuário!")
	}
}
