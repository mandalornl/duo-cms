import {stringify as queryStringify} from 'querystring';
import {merge} from 'lodash';

/**
 * Perform get request to json endpoint
 *
 * @param {string} uri
 * @param {{}} [parameters]
 *
 * @returns {Promise.<void>}
 */
const get = async (uri, parameters) =>
{
	const response = await fetch(uri + (parameters ? `?${queryStringify(parameters)}` : ''), {
		headers: {
			'content-type': 'application/json'
		},
		credentials: 'same-origin'
	});

	const result = await response.json();

	if (result.error)
	{
		throw result.error || 'An unknown error occurred';
	}

	return result.result;
};

/**
 * Perform post request to (json) endpoint
 *
 * @param {string} uri
 * @param {{}|FormData} [body = {}]
 *
 * @returns {Promise.<void>}
 */
const post = async (uri, body = {}) =>
{
	const options = merge({
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

	const result = await response.json();

	if (result.error)
	{
		throw result.error || 'An unknown error occurred';
	}

	return result.result;
};

export {get, post};