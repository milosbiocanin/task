export const GET =(url)=>{
	let token = document.querySelector('[name="auth-token"]').content
	return axios.get(`${url}?api_token=${token}`)
}
export const DELETE =(url)=>{
	let token = document.querySelector('[name="auth-token"]').content
	return axios.delete(`${url}?api_token=${token}`)
}
export const POST =(url, postData)=>{
	let token = document.querySelector('[name="auth-token"]').content
	return axios.post(`${url}?api_token=${token}&v=${new Date().getTime()}`, postData)
}