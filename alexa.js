'use strict';

const host = 'chess.pavelkoch.me';

// --------------- Helpers that build all of the responses -----------------------

function buildSpeechletResponse(title, output, repromptText, shouldEndSession) {
    return {
        outputSpeech: {
            type: 'PlainText',
            text: output,
        },
        card: {
            type: 'Simple',
            title: `SessionSpeechlet - ${title}`,
            content: `SessionSpeechlet - ${output}`,
        },
        reprompt: {
            outputSpeech: {
                type: 'PlainText',
                text: repromptText,
            },
        },
        shouldEndSession,
    };
}

function buildResponse(sessionAttributes, speechletResponse) {
    return {
        version: '1.0',
        sessionAttributes,
        response: speechletResponse,
    };
}

function post(host, port, path, data) {
    const http = require('http');

    const request = http.request({
        host, port, path,
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Content-Length': Buffer.byteLength(JSON.stringify(data)),
        },
    });

    request.write(JSON.stringify(data));

    request.end();
}

// --------------- Functions that control the skill's behavior -----------------------

function welcome(callback) {
    const cardTitle = 'Welcome';
    const speechOutput = 'Let us play some chess';
    const repromptText = '';
    const shouldEndSession = false;

    post(host, 80, '/chess/newgame.php', {});

    callback(buildSpeechletResponse(cardTitle, speechOutput, repromptText, shouldEndSession));
}

/**
 * Sets the color in the session and prepares the speech to reply to the user.
 */
function move(intent, callback) {
    const fieldTransform = {
        a: 0, b: 1, c: 2, d: 3, e: 4, f: 5, g: 6, h: 7,
        1: 0, 2: 1, 3: 2, 4: 3, 5: 4, 6: 5, 7: 6, 8: 7,
    };

    const speechOutput = 'Excellent move! Moving your ' + intent.slots.Piece.value + ' to ' + intent.slots.FileTwo.value.substr(0, 1) + intent.slots.RankTwo.value + ' now.';
    const slots = intent.slots;


    if (
        slots.Piece.value === undefined
        || slots.FileOne.value === undefined
        || slots.FileTwo.value === undefined
        || slots.RankOne.value === undefined
        || slots.RankTwo.value === undefined
    ) {
        return callback(buildSpeechletResponse(intent.name, 'I did not understand this notation.', '', false));
    }

    if (
        ! slots.Piece.value.match(/^(queen|king|rook|bishop|knight|pawn)$/)
        || ! slots.FileOne.value.match(/^(alfa|beta|charlie|delta|echo|foxtrot|golf|hotel)$/)
        || ! slots.FileTwo.value.match(/^(alfa|beta|charlie|delta|echo|foxtrot|golf|hotel)$/)
        || ! slots.RankOne.value.match(/^[12345678]$/)
        || ! slots.RankTwo.value.match(/^[12345678]$/)
    ) {
        return callback(buildSpeechletResponse(intent.name, 'I did not understand this notation.', '', false));
    }

    const data = {
        piece: slots.Piece.value.substr(0, 1),
        from: [
            fieldTransform[slots.FileOne.value.substr(0, 1)],
            fieldTransform[slots.RankOne.value],
        ],
        to: [
            fieldTransform[slots.FileTwo.value.substr(0, 1)],
            fieldTransform[slots.RankTwo.value],
        ],
    };

    post(host, 80, '/chess/move.php', data);

    if (slots.FileTwo.value == 'hotel' && slots.RankTwo.value == 5) {
        return callback(buildSpeechletResponse(intent.name, speechOutput + ' This results in a checkmate. Congratulations.', '', true));
    }

    return callback(buildSpeechletResponse(intent.name, speechOutput, '', false));
}

// --------------- Main handler -----------------------

exports.handler = (event, context, callback) => {  
    if (event.request.type === 'LaunchRequest') {
        welcome((speechletResponse) => {
            callback(null, buildResponse({}, speechletResponse));
        });
    }   

    if (event.request.type === 'IntentRequest' && event.request.intent.name == 'Move') {
        move(event.request.intent, (speechletResponse) => {
            callback(null, buildResponse({}, speechletResponse));
        });
    }
};
