/* global D3Funnel */

const data = {
	normal: [
		['Por Activar', 12000],
		['Activas', 4000],
		['Enviadas', 2500],
		['Recibida', 1500],
		['Cerradas', 700],
	]
};
const options = {
	basic: [data.normal, {}],
	formatted: [
		data.normal, {
			label: {
				format: '{l}\n+{f}',
			},
		},
	],
	curved: [
		data.normal, {
			chart: {
				curve: {
					enabled: true,
				},
			},
		},
	],
	pinch: [
		data.normal, {
			chart: {
				bottomPinch: 1,
			},
		},
	],
	gradient: [
		data.normal, {
			block: {
				fill: {
					type: 'gradient',
				},
			},
		},
	],
	inverted: [
		data.normal, {
			chart: {
				inverted: true,
			},
		},
	],
	hover: [
		data.normal, {
			block: {
				highlight: true,
			},
		},
	],
	dynamicHeight: [
		data.normal, {
			chart: {
				bottomWidth: 1 / 3,
			},
			block: {
				dynamicHeight: true,
			},
		},
	],
	dynamicSlope: [
		data.dynamicSlope, {
			block: {
				dynamicSlope: true,
			},
		},
	],
	minHeight: [
		data.minHeight, {
			block: {
				dynamicHeight: true,
				minHeight: 20,
			},
		},
	],
	animation: [
		data.normal, {
			chart: {
				animate: 200,
			},
		},
	],
	clickEvent: [
		data.normal, {
			events: {
				click: {
					block: (d) => {
						alert(`<${d.label.raw}> selected.`);
					},
				},
			},
		},
	],
	label: [
		data.normal, {
			label: {
				fontFamily: 'Open Sans',
				fontSize: '16px',
				fill: '#000',
			},
		},
	],
	color: [data.color, {}],
	labelsColor: [data.labelsColor, {}],
	valueOverlay: [
		data.normal, {
			block: {
				barOverlay: true,
			},
		},
	],
	works: [
		data.normal, {
			chart: {
				bottomPinch: 2,
				bottomWidth: 1 / 2,
				animate: 200,
				curve: {
					enabled: false,
				},
			},
			block: {
				dynamicHeight: true,
				fill: {
					type: 'gradient',
				},
				highlight: true,
			},
			events: {
				click: {
					block: (d) => {
						alert(`<${d.label.raw}> selected.`);
					},
				},
			},
		},
	],
};

const chart = new D3Funnel('#funnel');



	const index = 'gradient';

	// Reverse the dataset if the isInverted option is present
	// Otherwise, just use the regular data
	
		chart.draw(options[index][0], options[index][1]);
	


