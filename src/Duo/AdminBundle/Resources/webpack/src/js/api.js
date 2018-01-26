import $ from 'jquery';

/**
 * Handle response
 *
 * @param {Response} response
 *
 * @returns {Promise<null>}
 */
const handleResponse = async (response) =>
{
	if (response.status !== 200)
	{
		console.log('Fetch error, resulted with status code: %s', response.status);

		return null;
	}

	const data = await response.json();

	if (data.error)
	{
		console.error(data.error);

		return null;
	}

	return data;
};

/**
 * Perform get request to json endpoint
 *
 * @param {string} uri
 * @param {Object<null>} [parameters]
 *
 * @returns {Promise.<*>}
 */
const get = async (uri, parameters = null) =>
{
	try
	{
		const response = await fetch(uri + (parameters ? `?${$.param(parameters)}` : ''), {
			headers: {
				'content-type': 'application/json'
			},
			credentials: 'same-origin'
		});

		return handleResponse(response);
	}
	catch (err)
	{
		console.error(err);

		return null;
	}
};

/**
 * Perform post request to (json) endpoint
 *
 * @param {string} uri
 * @param {{}|FormData} [body = {}]
 *
 * @returns {Promise.<*>}
 */
const post = async (uri, body = {}) =>
{
	try
	{
		const options = $.extend({
			method: 'POST',
			credentials: 'same-origin'
		}, (() =>
		{
			if (body instanceof FormData)
			{
				return {
					body: body
				};
			}

			return {
				headers: {
					'content-type': 'application/json'
				},
				body: JSON.stringify(body)
			};
		})());

		const response = await fetch(uri, options);

		return handleResponse(response);
	}
	catch (err)
	{
		console.error(err);

		return null;
	}
};

export {get, post};