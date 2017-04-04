function TextInputValidation(field)
{
	if (field == "")
		return false;
	return true;
}

function EmailValidation(field) {
	if (!((field.indexOf(".") > 0) && field.indexOf("@") > 0) || /[^a-zA-Z0-9.@_-]/.test(field))
		return false;
	return true;
}