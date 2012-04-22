/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009, 2012 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 *
 *  Huffman encoding/decoding derived from code at http://rumkin.com/tools/compression/compress_huff.php
 */

// version 1 of PGN encoding:
//   encodedPGN = nnn$xxx0
//   nnn = number representing bytes length of the decoded message
//   $ = dollar char (delimiter for length info)
//   xxx = encoded text (using LetterCodes below)
//   0 = zero char (version marker)

var encodingCharSet_dec;
var encodingCharSet_enc;
var encodingCharSet = encodingCharSet_dec = "$0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";
var encodingVersion_dec;
var encodingVersion_enc;
var encodingVersion = encodingVersion_dec = 1;

if (((encodingCharSet_enc != undefined) && (encodingCharSet_enc != encodingCharSet_dec)) ||
    ((encodingVersion_enc != undefined) && (encodingVersion_enc != encodingVersion_dec))) {
  errorString = "error: PGN encoding/decoding mismatch";
  if (typeof myAlert == "function") { myAlert(errorString); }
  else { alert(errorString); }
}

function DecodePGN(bytes) {

  if (bytes.charAt(bytes.length - 1) != encodingCharSet.charAt(encodingVersion)) {
    errorString = "error: PGN encoding version mismatch (e:" +
                  bytes.charAt(bytes.length - 1) + " d:" + encodingCharSet.charAt(encodingVersion) + ")";
    if (typeof myAlert == "function") { myAlert(errorString); }
    else { alert(errorString); }
  } else {
    bytes.length--;
  }

  originalLength = parseInt(bytes.match(/^[0-9]*/), 10);
  bytes = bytes.replace(/^[0-9]*\$/,"");

  l = new Array();
  l[0] = -146;
  l[1] = -111;
  l[2] = -66;
  l[3] = -55;
  l[4] = -6;
  l[5] = 46;
  l[6] = -8;
  l[7] = 66;
  l[8] = -10;
  l[9] = 105;
  l[10] = -12;
  l[11] = 107;
  l[12] = -14;
  l[13] = 44;
  l[14] = -16;
  l[15] = 85;
  l[16] = -18;
  l[17] = 106;
  l[18] = -20;
  l[19] = 62;
  l[20] = -22;
  l[21] = 89;
  l[22] = -24;
  l[23] = 38;
  l[24] = -40;
  l[25] = -33;
  l[26] = -30;
  l[27] = -29;
  l[28] = 17;
  l[29] = 18;
  l[30] = -32;
  l[31] = 15;
  l[32] = 16;
  l[33] = -37;
  l[34] = -36;
  l[35] = 21;
  l[36] = 22;
  l[37] = -39;
  l[38] = 19;
  l[39] = 20;
  l[40] = -48;
  l[41] = -45;
  l[42] = -44;
  l[43] = 7;
  l[44] = 8;
  l[45] = -47;
  l[46] = 5;
  l[47] = 6;
  l[48] = -52;
  l[49] = -51;
  l[50] = 12;
  l[51] = 14;
  l[52] = -54;
  l[53] = 9;
  l[54] = 11;
  l[55] = -57;
  l[56] = 34;
  l[57] = -59;
  l[58] = 78;
  l[59] = -61;
  l[60] = 57;
  l[61] = -63;
  l[62] = 63;
  l[63] = -65;
  l[64] = 109;
  l[65] = 119;
  l[66] = -80;
  l[67] = -69;
  l[68] = 101;
  l[69] = -71;
  l[70] = 55;
  l[71] = -73;
  l[72] = 69;
  l[73] = -75;
  l[74] = 118;
  l[75] = -77;
  l[76] = 121;
  l[77] = -79;
  l[78] = 73;
  l[79] = 123;
  l[80] = -82;
  l[81] = 49;
  l[82] = -86;
  l[83] = -85;
  l[84] = 13;
  l[85] = 40;
  l[86] = -88;
  l[87] = 45;
  l[88] = -90;
  l[89] = 68;
  l[90] = -92;
  l[91] = 84;
  l[92] = -94;
  l[93] = 125;
  l[94] = -96;
  l[95] = 39;
  l[96] = -98;
  l[97] = 58;
  l[98] = -100;
  l[99] = 36;
  l[100] = -102;
  l[101] = 92;
  l[102] = -104;
  l[103] = 124;
  l[104] = -108;
  l[105] = -107;
  l[106] = 3;
  l[107] = 4;
  l[108] = -110;
  l[109] = 0;
  l[110] = 2;
  l[111] = -129;
  l[112] = -118;
  l[113] = -115;
  l[114] = 50;
  l[115] = -117;
  l[116] = 102;
  l[117] = 103;
  l[118] = -122;
  l[119] = -121;
  l[120] = 48;
  l[121] = 110;
  l[122] = -126;
  l[123] = -125;
  l[124] = 10;
  l[125] = 1;
  l[126] = -128;
  l[127] = 79;
  l[128] = 117;
  l[129] = -137;
  l[130] = -132;
  l[131] = 51;
  l[132] = -134;
  l[133] = 98;
  l[134] = -136;
  l[135] = 47;
  l[136] = 112;
  l[137] = -141;
  l[138] = -140;
  l[139] = 56;
  l[140] = 81;
  l[141] = -143;
  l[142] = 104;
  l[143] = -145;
  l[144] = 114;
  l[145] = 115;
  l[146] = -192;
  l[147] = -153;
  l[148] = -150;
  l[149] = 32;
  l[150] = -152;
  l[151] = 91;
  l[152] = 93;
  l[153] = -157;
  l[154] = -156;
  l[155] = 99;
  l[156] = 120;
  l[157] = -159;
  l[158] = 116;
  l[159] = -161;
  l[160] = 75;
  l[161] = -163;
  l[162] = 80;
  l[163] = -165;
  l[164] = 67;
  l[165] = -167;
  l[166] = 77;
  l[167] = -169;
  l[168] = 90;
  l[169] = -171;
  l[170] = 60;
  l[171] = -173;
  l[172] = 33;
  l[173] = -175;
  l[174] = 37;
  l[175] = -177;
  l[176] = 96;
  l[177] = -185;
  l[178] = -182;
  l[179] = -181;
  l[180] = 248;
  l[181] = 249;
  l[182] = -184;
  l[183] = 246;
  l[184] = 247;
  l[185] = -189;
  l[186] = -188;
  l[187] = 252;
  l[188] = 253;
  l[189] = -191;
  l[190] = 250;
  l[191] = 251;
  l[192] = -228;
  l[193] = -225;
  l[194] = -196;
  l[195] = 52;
  l[196] = -198;
  l[197] = 108;
  l[198] = -208;
  l[199] = -201;
  l[200] = 83;
  l[201] = -205;
  l[202] = -204;
  l[203] = 61;
  l[204] = 72;
  l[205] = -207;
  l[206] = 113;
  l[207] = 122;
  l[208] = -212;
  l[209] = -211;
  l[210] = 65;
  l[211] = 70;
  l[212] = -214;
  l[213] = 71;
  l[214] = -216;
  l[215] = 76;
  l[216] = -218;
  l[217] = 74;
  l[218] = -220;
  l[219] = 88;
  l[220] = -222;
  l[221] = 64;
  l[222] = -224;
  l[223] = 42;
  l[224] = 94;
  l[225] = -227;
  l[226] = 53;
  l[227] = 100;
  l[228] = -232;
  l[229] = -231;
  l[230] = 54;
  l[231] = 82;
  l[232] = -234;
  l[233] = 97;
  l[234] = -236;
  l[235] = 111;
  l[236] = -238;
  l[237] = 43;
  l[238] = -240;
  l[239] = 87;
  l[240] = -242;
  l[241] = 41;
  l[242] = -244;
  l[243] = 86;
  l[244] = -246;
  l[245] = 35;
  l[246] = -256;
  l[247] = -249;
  l[248] = 59;
  l[249] = -251;
  l[250] = 95;
  l[251] = -253;
  l[252] = 126;
  l[253] = -255;
  l[254] = 254;
  l[255] = 255;
  l[256] = -384;
  l[257] = -321;
  l[258] = -290;
  l[259] = -275;
  l[260] = -268;
  l[261] = -265;
  l[262] = -264;
  l[263] = 160;
  l[264] = 161;
  l[265] = -267;
  l[266] = 158;
  l[267] = 159;
  l[268] = -272;
  l[269] = -271;
  l[270] = 164;
  l[271] = 165;
  l[272] = -274;
  l[273] = 162;
  l[274] = 163;
  l[275] = -283;
  l[276] = -280;
  l[277] = -279;
  l[278] = 152;
  l[279] = 153;
  l[280] = -282;
  l[281] = 150;
  l[282] = 151;
  l[283] = -287;
  l[284] = -286;
  l[285] = 156;
  l[286] = 157;
  l[287] = -289;
  l[288] = 154;
  l[289] = 155;
  l[290] = -306;
  l[291] = -299;
  l[292] = -296;
  l[293] = -295;
  l[294] = 176;
  l[295] = 177;
  l[296] = -298;
  l[297] = 174;
  l[298] = 175;
  l[299] = -303;
  l[300] = -302;
  l[301] = 180;
  l[302] = 181;
  l[303] = -305;
  l[304] = 178;
  l[305] = 179;
  l[306] = -314;
  l[307] = -311;
  l[308] = -310;
  l[309] = 168;
  l[310] = 169;
  l[311] = -313;
  l[312] = 166;
  l[313] = 167;
  l[314] = -318;
  l[315] = -317;
  l[316] = 172;
  l[317] = 173;
  l[318] = -320;
  l[319] = 170;
  l[320] = 171;
  l[321] = -353;
  l[322] = -338;
  l[323] = -331;
  l[324] = -328;
  l[325] = -327;
  l[326] = 128;
  l[327] = 129;
  l[328] = -330;
  l[329] = 31;
  l[330] = 127;
  l[331] = -335;
  l[332] = -334;
  l[333] = 132;
  l[334] = 133;
  l[335] = -337;
  l[336] = 130;
  l[337] = 131;
  l[338] = -346;
  l[339] = -343;
  l[340] = -342;
  l[341] = 25;
  l[342] = 26;
  l[343] = -345;
  l[344] = 23;
  l[345] = 24;
  l[346] = -350;
  l[347] = -349;
  l[348] = 29;
  l[349] = 30;
  l[350] = -352;
  l[351] = 27;
  l[352] = 28;
  l[353] = -369;
  l[354] = -362;
  l[355] = -359;
  l[356] = -358;
  l[357] = 144;
  l[358] = 145;
  l[359] = -361;
  l[360] = 142;
  l[361] = 143;
  l[362] = -366;
  l[363] = -365;
  l[364] = 148;
  l[365] = 149;
  l[366] = -368;
  l[367] = 146;
  l[368] = 147;
  l[369] = -377;
  l[370] = -374;
  l[371] = -373;
  l[372] = 136;
  l[373] = 137;
  l[374] = -376;
  l[375] = 134;
  l[376] = 135;
  l[377] = -381;
  l[378] = -380;
  l[379] = 140;
  l[380] = 141;
  l[381] = -383;
  l[382] = 138;
  l[383] = 139;
  l[384] = -448;
  l[385] = -417;
  l[386] = -402;
  l[387] = -395;
  l[388] = -392;
  l[389] = -391;
  l[390] = 224;
  l[391] = 225;
  l[392] = -394;
  l[393] = 222;
  l[394] = 223;
  l[395] = -399;
  l[396] = -398;
  l[397] = 228;
  l[398] = 229;
  l[399] = -401;
  l[400] = 226;
  l[401] = 227;
  l[402] = -410;
  l[403] = -407;
  l[404] = -406;
  l[405] = 216;
  l[406] = 217;
  l[407] = -409;
  l[408] = 214;
  l[409] = 215;
  l[410] = -414;
  l[411] = -413;
  l[412] = 220;
  l[413] = 221;
  l[414] = -416;
  l[415] = 218;
  l[416] = 219;
  l[417] = -433;
  l[418] = -426;
  l[419] = -423;
  l[420] = -422;
  l[421] = 240;
  l[422] = 241;
  l[423] = -425;
  l[424] = 238;
  l[425] = 239;
  l[426] = -430;
  l[427] = -429;
  l[428] = 244;
  l[429] = 245;
  l[430] = -432;
  l[431] = 242;
  l[432] = 243;
  l[433] = -441;
  l[434] = -438;
  l[435] = -437;
  l[436] = 232;
  l[437] = 233;
  l[438] = -440;
  l[439] = 230;
  l[440] = 231;
  l[441] = -445;
  l[442] = -444;
  l[443] = 236;
  l[444] = 237;
  l[445] = -447;
  l[446] = 234;
  l[447] = 235;
  l[448] = -480;
  l[449] = -465;
  l[450] = -458;
  l[451] = -455;
  l[452] = -454;
  l[453] = 192;
  l[454] = 193;
  l[455] = -457;
  l[456] = 190;
  l[457] = 191;
  l[458] = -462;
  l[459] = -461;
  l[460] = 196;
  l[461] = 197;
  l[462] = -464;
  l[463] = 194;
  l[464] = 195;
  l[465] = -473;
  l[466] = -470;
  l[467] = -469;
  l[468] = 184;
  l[469] = 185;
  l[470] = -472;
  l[471] = 182;
  l[472] = 183;
  l[473] = -477;
  l[474] = -476;
  l[475] = 188;
  l[476] = 189;
  l[477] = -479;
  l[478] = 186;
  l[479] = 187;
  l[480] = -496;
  l[481] = -489;
  l[482] = -486;
  l[483] = -485;
  l[484] = 208;
  l[485] = 209;
  l[486] = -488;
  l[487] = 206;
  l[488] = 207;
  l[489] = -493;
  l[490] = -492;
  l[491] = 212;
  l[492] = 213;
  l[493] = -495;
  l[494] = 210;
  l[495] = 211;
  l[496] = -504;
  l[497] = -501;
  l[498] = -500;
  l[499] = 200;
  l[500] = 201;
  l[501] = -503;
  l[502] = 198;
  l[503] = 199;
  l[504] = -508;
  l[505] = -507;
  l[506] = 204;
  l[507] = 205;
  l[508] = -510;
  l[509] = 202;
  l[510] = 203;

  e=b=a=0;
  o="";

  function B() { if (a===0) { b=encodingCharSet.indexOf(bytes.charAt(e++)); a=6; } return ((b>>--a)&0x01); }

  while(originalLength>0) { i=0;
    while(l[i]<0) {
      if (B()) { i=-l[i]; }
      else { i++; }
    }
    o+=String.fromCharCode(l[i]);
    originalLength--;
  }

  return o;
}

