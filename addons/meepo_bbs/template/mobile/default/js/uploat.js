var JpegMeta = {};
JpegMeta.stringIsClean = function(a) {
    for (var b = 0; b < a.length; b++) if (a.charCodeAt(b) < 32) return ! 1;
    return ! 0
},
JpegMeta.parseNum = function(a, b, c, d) {
    var e, f, g = ">" === a;
    for (void 0 === c && (c = 0), void 0 === d && (d = b.length - c), e = g ? c: c + d - 1; g ? c + d > e: e >= c; g ? e++:e--) f <<= 8,
    f += b.charCodeAt(e);
    return f
},
JpegMeta.parseSnum = function(a, b, c, d) {
    var e, f, g, h = ">" === a;
    for (void 0 === c && (c = 0), void 0 === d && (d = b.length - c), e = h ? c: c + d - 1; h ? c + d > e: e >= c; h ? e++:e--) void 0 === g && (g = 128 === (128 & b.charCodeAt(e))),
    f <<= 8,
    f += g ? 255 & ~b.charCodeAt(e) : b.charCodeAt(e);
    return g && (f += 1, f *= -1),
    f
},
JpegMeta.Rational = function(a, b) {
    return this.num = a,
    this.den = b || 1,
    this
},
JpegMeta.Rational.prototype.toString = function() {
    return 0 === this.num ? "" + this.num: 1 === this.den ? "" + this.num: 1 === this.num ? this.num + " / " + this.den: this.num / this.den
},
JpegMeta.Rational.prototype.asFloat = function() {
    return this.num / this.den
},
JpegMeta.MetaGroup = function(a, b) {
    return this.fieldName = a,
    this.description = b,
    this.metaProps = {},
    this
},
JpegMeta.MetaGroup.prototype._addProperty = function(a, b, c) {
    var d = new JpegMeta.MetaProp(a, b, c);
    this[d.fieldName] = d,
    this.metaProps[d.fieldName] = d
},
JpegMeta.MetaGroup.prototype.toString = function() {
    return "[MetaGroup " + this.description + "]"
},
JpegMeta.MetaProp = function(a, b, c) {
    return this.fieldName = a,
    this.description = b,
    this.value = c,
    this
},
JpegMeta.MetaProp.prototype.toString = function() {
    return "" + this.value
},
this.JpegMeta.JpegFile = function(a, b) {
    var c = this._SOS;
    this.metaGroups = {},
    this._binary_data = a,
    this.filename = b;
    var d, e, f, g, h, i, j, k = 0,
    l = 0;
    if (this._binary_data.slice(0, 2) !== this._SOI_MARKER) throw new Error("Doesn't look like a JPEG file. First two bytes are " + this._binary_data.charCodeAt(0) + "," + this._binary_data.charCodeAt(1) + ".");
    for (k += 2; k < this._binary_data.length && (d = this._binary_data.charCodeAt(k++), e = this._binary_data.charCodeAt(k++), l = k, d == this._DELIM) && e !== c;) {
        for (h = JpegMeta.parseNum(">", this._binary_data, k, 2), k += h; k < this._binary_data.length;) if (d = this._binary_data.charCodeAt(k++), d == this._DELIM && (f = this._binary_data.charCodeAt(k++), 0 != f)) {
            k -= 2;
            break
        }
        g = k - l,
        this._markers[e] ? (i = this._markers[e][0], j = this._markers[e][1]) : (i = "UNKN", j = void 0),
        j && this[j](e, l + 2)
    }
    if (void 0 === this.general) throw Error("Invalid JPEG file.");
    return this
},
this.JpegMeta.JpegFile.prototype.toString = function() {
    return "[JpegFile " + this.filename + " " + this.general.type + " " + this.general.pixelWidth + "x" + this.general.pixelHeight + " Depth: " + this.general.depth + "]"
},
this.JpegMeta.JpegFile.prototype._SOI_MARKER = "ÿØ",
this.JpegMeta.JpegFile.prototype._DELIM = 255,
this.JpegMeta.JpegFile.prototype._EOI = 217,
this.JpegMeta.JpegFile.prototype._SOS = 218,
this.JpegMeta.JpegFile.prototype._sofHandler = function(a, b) {
    if (void 0 !== this.general) throw Error("Unexpected multiple-frame image");
    this._addMetaGroup("general", "General"),
    this.general._addProperty("depth", "Depth", JpegMeta.parseNum(">", this._binary_data, b, 1)),
    this.general._addProperty("pixelHeight", "Pixel Height", JpegMeta.parseNum(">", this._binary_data, b + 1, 2)),
    this.general._addProperty("pixelWidth", "Pixel Width", JpegMeta.parseNum(">", this._binary_data, b + 3, 2)),
    this.general._addProperty("type", "Type", this._markers[a][2])
},
this.JpegMeta.JpegFile.prototype._JFIF_IDENT = "JFIF\x00",
this.JpegMeta.JpegFile.prototype._JFXX_IDENT = "JFXX\x00",
this.JpegMeta.JpegFile.prototype._EXIF_IDENT = "Exif\x00",
this.JpegMeta.JpegFile.prototype._types = {
    1 : ["BYTE", 1],
    2 : ["ASCII", 1],
    3 : ["SHORT", 2],
    4 : ["LONG", 4],
    5 : ["RATIONAL", 8],
    6 : ["SBYTE", 1],
    7 : ["UNDEFINED", 1],
    8 : ["SSHORT", 2],
    9 : ["SLONG", 4],
    10 : ["SRATIONAL", 8],
    11 : ["FLOAT", 4],
    12 : ["DOUBLE", 8]
},
this.JpegMeta.JpegFile.prototype._tifftags = {
    256 : ["Image width", "ImageWidth"],
    257 : ["Image height", "ImageLength"],
    258 : ["Number of bits per component", "BitsPerSample"],
    259 : ["Compression scheme", "Compression", {
        1 : "uncompressed",
        6 : "JPEG compression"
    }],
    262 : ["Pixel composition", "PhotmetricInerpretation", {
        2 : "RGB",
        6 : "YCbCr"
    }],
    274 : ["Orientation of image", "Orientation", {
        1 : "Normal",
        2 : "Reverse?",
        3 : "Upside-down",
        4 : "Upside-down Reverse",
        5 : "90 degree CW",
        6 : "90 degree CW reverse",
        7 : "90 degree CCW",
        8 : "90 degree CCW reverse"
    }],
    277 : ["Number of components", "SamplesPerPixel"],
    284 : ["Image data arrangement", "PlanarConfiguration", {
        1 : "chunky format",
        2 : "planar format"
    }],
    530 : ["Subsampling ratio of Y to C", "YCbCrSubSampling"],
    531 : ["Y and C positioning", "YCbCrPositioning", {
        1 : "centered",
        2 : "co-sited"
    }],
    282 : ["X Resolution", "XResolution"],
    283 : ["Y Resolution", "YResolution"],
    296 : ["Resolution Unit", "ResolutionUnit", {
        2 : "inches",
        3 : "centimeters"
    }],
    273 : ["Image data location", "StripOffsets"],
    278 : ["Number of rows per strip", "RowsPerStrip"],
    279 : ["Bytes per compressed strip", "StripByteCounts"],
    513 : ["Offset to JPEG SOI", "JPEGInterchangeFormat"],
    514 : ["Bytes of JPEG Data", "JPEGInterchangeFormatLength"],
    301 : ["Transfer function", "TransferFunction"],
    318 : ["White point chromaticity", "WhitePoint"],
    319 : ["Chromaticities of primaries", "PrimaryChromaticities"],
    529 : ["Color space transformation matrix coefficients", "YCbCrCoefficients"],
    532 : ["Pair of black and white reference values", "ReferenceBlackWhite"],
    306 : ["Date and time", "DateTime"],
    270 : ["Image title", "ImageDescription"],
    271 : ["Make", "Make"],
    272 : ["Model", "Model"],
    305 : ["Software", "Software"],
    315 : ["Person who created the image", "Artist"],
    316 : ["Host Computer", "HostComputer"],
    33432 : ["Copyright holder", "Copyright"],
    34665 : ["Exif tag", "ExifIfdPointer"],
    34853 : ["GPS tag", "GPSInfoIfdPointer"]
},
this.JpegMeta.JpegFile.prototype._exiftags = {
    36864 : ["Exif Version", "ExifVersion"],
    40960 : ["FlashPix Version", "FlashpixVersion"],
    40961 : ["Color Space", "ColorSpace"],
    37121 : ["Meaning of each component", "ComponentsConfiguration"],
    37122 : ["Compressed Bits Per Pixel", "CompressedBitsPerPixel"],
    40962 : ["Pixel X Dimension", "PixelXDimension"],
    40963 : ["Pixel Y Dimension", "PixelYDimension"],
    37500 : ["Manufacturer notes", "MakerNote"],
    37510 : ["User comments", "UserComment"],
    40964 : ["Related audio file", "RelatedSoundFile"],
    36867 : ["Date Time Original", "DateTimeOriginal"],
    36868 : ["Date Time Digitized", "DateTimeDigitized"],
    37520 : ["DateTime subseconds", "SubSecTime"],
    37521 : ["DateTimeOriginal subseconds", "SubSecTimeOriginal"],
    37522 : ["DateTimeDigitized subseconds", "SubSecTimeDigitized"],
    33434 : ["Exposure time", "ExposureTime"],
    33437 : ["FNumber", "FNumber"],
    34850 : ["Exposure program", "ExposureProgram"],
    34852 : ["Spectral sensitivity", "SpectralSensitivity"],
    34855 : ["ISO Speed Ratings", "ISOSpeedRatings"],
    34856 : ["Optoelectric coefficient", "OECF"],
    37377 : ["Shutter Speed", "ShutterSpeedValue"],
    37378 : ["Aperture Value", "ApertureValue"],
    37379 : ["Brightness", "BrightnessValue"],
    37380 : ["Exposure Bias Value", "ExposureBiasValue"],
    37381 : ["Max Aperture Value", "MaxApertureValue"],
    37382 : ["Subject Distance", "SubjectDistance"],
    37383 : ["Metering Mode", "MeteringMode"],
    37384 : ["Light Source", "LightSource"],
    37385 : ["Flash", "Flash"],
    37386 : ["Focal Length", "FocalLength"],
    37396 : ["Subject Area", "SubjectArea"],
    41483 : ["Flash Energy", "FlashEnergy"],
    41484 : ["Spatial Frequency Response", "SpatialFrequencyResponse"],
    41486 : ["Focal Plane X Resolution", "FocalPlaneXResolution"],
    41487 : ["Focal Plane Y Resolution", "FocalPlaneYResolution"],
    41488 : ["Focal Plane Resolution Unit", "FocalPlaneResolutionUnit"],
    41492 : ["Subject Location", "SubjectLocation"],
    41493 : ["Exposure Index", "ExposureIndex"],
    41495 : ["Sensing Method", "SensingMethod"],
    41728 : ["File Source", "FileSource"],
    41729 : ["Scene Type", "SceneType"],
    41730 : ["CFA Pattern", "CFAPattern"],
    41985 : ["Custom Rendered", "CustomRendered"],
    41986 : ["Exposure Mode", "Exposure Mode"],
    41987 : ["White Balance", "WhiteBalance"],
    41988 : ["Digital Zoom Ratio", "DigitalZoomRatio"],
    41989 : ["Focal length in 35 mm film", "FocalLengthIn35mmFilm"],
    41990 : ["Scene Capture Type", "SceneCaptureType"],
    41991 : ["Gain Control", "GainControl"],
    41992 : ["Contrast", "Contrast"],
    41993 : ["Saturation", "Saturation"],
    41994 : ["Sharpness", "Sharpness"],
    41995 : ["Device settings description", "DeviceSettingDescription"],
    41996 : ["Subject distance range", "SubjectDistanceRange"],
    42016 : ["Unique image ID", "ImageUniqueID"],
    40965 : ["Interoperability tag", "InteroperabilityIFDPointer"]
},
this.JpegMeta.JpegFile.prototype._gpstags = {
    0 : ["GPS tag version", "GPSVersionID"],
    1 : ["North or South Latitude", "GPSLatitudeRef"],
    2 : ["Latitude", "GPSLatitude"],
    3 : ["East or West Longitude", "GPSLongitudeRef"],
    4 : ["Longitude", "GPSLongitude"],
    5 : ["Altitude reference", "GPSAltitudeRef"],
    6 : ["Altitude", "GPSAltitude"],
    7 : ["GPS time (atomic clock)", "GPSTimeStamp"],
    8 : ["GPS satellites usedd for measurement", "GPSSatellites"],
    9 : ["GPS receiver status", "GPSStatus"],
    10 : ["GPS mesaurement mode", "GPSMeasureMode"],
    11 : ["Measurement precision", "GPSDOP"],
    12 : ["Speed unit", "GPSSpeedRef"],
    13 : ["Speed of GPS receiver", "GPSSpeed"],
    14 : ["Reference for direction of movement", "GPSTrackRef"],
    15 : ["Direction of movement", "GPSTrack"],
    16 : ["Reference for direction of image", "GPSImgDirectionRef"],
    17 : ["Direction of image", "GPSImgDirection"],
    18 : ["Geodetic survey data used", "GPSMapDatum"],
    19 : ["Reference for latitude of destination", "GPSDestLatitudeRef"],
    20 : ["Latitude of destination", "GPSDestLatitude"],
    21 : ["Reference for longitude of destination", "GPSDestLongitudeRef"],
    22 : ["Longitude of destination", "GPSDestLongitude"],
    23 : ["Reference for bearing of destination", "GPSDestBearingRef"],
    24 : ["Bearing of destination", "GPSDestBearing"],
    25 : ["Reference for distance to destination", "GPSDestDistanceRef"],
    26 : ["Distance to destination", "GPSDestDistance"],
    27 : ["Name of GPS processing method", "GPSProcessingMethod"],
    28 : ["Name of GPS area", "GPSAreaInformation"],
    29 : ["GPS Date", "GPSDateStamp"],
    30 : ["GPS differential correction", "GPSDifferential"]
},
this.JpegMeta.JpegFile.prototype._markers = {
    192 : ["SOF0", "_sofHandler", "Baseline DCT"],
    193 : ["SOF1", "_sofHandler", "Extended sequential DCT"],
    194 : ["SOF2", "_sofHandler", "Progressive DCT"],
    195 : ["SOF3", "_sofHandler", "Lossless (sequential)"],
    197 : ["SOF5", "_sofHandler", "Differential sequential DCT"],
    198 : ["SOF6", "_sofHandler", "Differential progressive DCT"],
    199 : ["SOF7", "_sofHandler", "Differential lossless (sequential)"],
    200 : ["JPG", null, "Reserved for JPEG extensions"],
    201 : ["SOF9", "_sofHandler", "Extended sequential DCT"],
    202 : ["SOF10", "_sofHandler", "Progressive DCT"],
    203 : ["SOF11", "_sofHandler", "Lossless (sequential)"],
    205 : ["SOF13", "_sofHandler", "Differential sequential DCT"],
    206 : ["SOF14", "_sofHandler", "Differential progressive DCT"],
    207 : ["SOF15", "_sofHandler", "Differential lossless (sequential)"],
    196 : ["DHT", null, "Define Huffman table(s)"],
    204 : ["DAC", null, "Define arithmetic coding conditioning(s)"],
    208 : ["RST0", null, "Restart with modulo 8 count “0”"],
    209 : ["RST1", null, "Restart with modulo 8 count “1”"],
    210 : ["RST2", null, "Restart with modulo 8 count “2”"],
    211 : ["RST3", null, "Restart with modulo 8 count “3”"],
    212 : ["RST4", null, "Restart with modulo 8 count “4”"],
    213 : ["RST5", null, "Restart with modulo 8 count “5”"],
    214 : ["RST6", null, "Restart with modulo 8 count “6”"],
    215 : ["RST7", null, "Restart with modulo 8 count “7”"],
    216 : ["SOI", null, "Start of image"],
    217 : ["EOI", null, "End of image"],
    218 : ["SOS", null, "Start of scan"],
    219 : ["DQT", null, "Define quantization table(s)"],
    220 : ["DNL", null, "Define number of lines"],
    221 : ["DRI", null, "Define restart interval"],
    222 : ["DHP", null, "Define hierarchical progression"],
    223 : ["EXP", null, "Expand reference component(s)"],
    224 : ["APP0", "_app0Handler", "Reserved for application segments"],
    225 : ["APP1", "_app1Handler"],
    226 : ["APP2", null],
    227 : ["APP3", null],
    228 : ["APP4", null],
    229 : ["APP5", null],
    230 : ["APP6", null],
    231 : ["APP7", null],
    232 : ["APP8", null],
    233 : ["APP9", null],
    234 : ["APP10", null],
    235 : ["APP11", null],
    236 : ["APP12", null],
    237 : ["APP13", null],
    238 : ["APP14", null],
    239 : ["APP15", null],
    240 : ["JPG0", null],
    241 : ["JPG1", null],
    242 : ["JPG2", null],
    243 : ["JPG3", null],
    244 : ["JPG4", null],
    245 : ["JPG5", null],
    246 : ["JPG6", null],
    247 : ["JPG7", null],
    248 : ["JPG8", null],
    249 : ["JPG9", null],
    250 : ["JPG10", null],
    251 : ["JPG11", null],
    252 : ["JPG12", null],
    253 : ["JPG13", null],
    254 : ["COM", null],
    1 : ["JPG13", null]
},
this.JpegMeta.JpegFile.prototype._addMetaGroup = function(a, b) {
    var c = new JpegMeta.MetaGroup(a, b);
    return this[c.fieldName] = c,
    this.metaGroups[c.fieldName] = c,
    c
},
this.JpegMeta.JpegFile.prototype._parseIfd = function(a, b, c, d, e, f, g) {
    var h, i, j, k, l, m, n, o, p, q, r, s = JpegMeta.parseNum(a, b, c + d, 2);
    r = this._addMetaGroup(f, g);
    for (var t = 0; s > t; t++) if (h = c + d + 2 + 12 * t, i = JpegMeta.parseNum(a, b, h, 2), k = JpegMeta.parseNum(a, b, h + 2, 2), m = JpegMeta.parseNum(a, b, h + 4, 4), n = JpegMeta.parseNum(a, b, h + 8, 4), void 0 !== this._types[k]) {
        if (j = this._types[k][0], l = this._types[k][1], n = 4 >= l * m ? h + 8 : c + n, "UNDEFINED" == j) o = void 0;
        else if ("ASCII" == j) o = b.slice(n, n + m),
        o = o.split("\x00")[0],
        JpegMeta.stringIsClean(o) || (o = "");
        else {
            o = new Array;
            for (var u = 0; m > u; u++, n += l)("BYTE" == j || "SHORT" == j || "LONG" == j) && o.push(JpegMeta.parseNum(a, b, n, l)),
            ("SBYTE" == j || "SSHORT" == j || "SLONG" == j) && o.push(JpegMeta.parseSnum(a, b, n, l)),
            "RATIONAL" == j && (p = JpegMeta.parseNum(a, b, n, 4), q = JpegMeta.parseNum(a, b, n + 4, 4), o.push(new JpegMeta.Rational(p, q))),
            "SRATIONAL" == j && (p = JpegMeta.parseSnum(a, b, n, 4), q = JpegMeta.parseSnum(a, b, n + 4, 4), o.push(new JpegMeta.Rational(p, q))),
            o.push();
            1 === m && (o = o[0])
        }
        e.hasOwnProperty(i) ? r._addProperty(e[i][1], e[i][0], o) : console.log("WARNING(jpegmeta.js): Unknown tag: ", i)
    }
},
this.JpegMeta.JpegFile.prototype._jfifHandler = function(a, b) {
    if (void 0 !== this.jfif) throw Error("Multiple JFIF segments found");
    this._addMetaGroup("jfif", "JFIF"),
    this.jfif._addProperty("version_major", "Version Major", this._binary_data.charCodeAt(b + 5)),
    this.jfif._addProperty("version_minor", "Version Minor", this._binary_data.charCodeAt(b + 6)),
    this.jfif._addProperty("version", "JFIF Version", this.jfif.version_major.value + "." + this.jfif.version_minor.value),
    this.jfif._addProperty("units", "Density Unit", this._binary_data.charCodeAt(b + 7)),
    this.jfif._addProperty("Xdensity", "X density", JpegMeta.parseNum(">", this._binary_data, b + 8, 2)),
    this.jfif._addProperty("Ydensity", "Y Density", JpegMeta.parseNum(">", this._binary_data, b + 10, 2)),
    this.jfif._addProperty("Xthumbnail", "X Thumbnail", JpegMeta.parseNum(">", this._binary_data, b + 12, 1)),
    this.jfif._addProperty("Ythumbnail", "Y Thumbnail", JpegMeta.parseNum(">", this._binary_data, b + 13, 1))
},
this.JpegMeta.JpegFile.prototype._app0Handler = function(a, b) {
    var c = this._binary_data.slice(b, b + 5);
    c == this._JFIF_IDENT ? this._jfifHandler(a, b) : c == this._JFXX_IDENT
},
this.JpegMeta.JpegFile.prototype._app1Handler = function(a, b) {
    var c = this._binary_data.slice(b, b + 5);
    c == this._EXIF_IDENT && this._exifHandler(a, b + 6)
},
JpegMeta.JpegFile.prototype._exifHandler = function(a, b) {
    if (void 0 !== this.exif) throw new Error("Multiple JFIF segments found");
    var c, d, e, f = this._binary_data.slice(b, b + 2);
    if ("II" === f) c = "<";
    else {
        if ("MM" !== f) throw new Error("Malformed TIFF meta-data. Unknown endianess: " + f);
        c = ">"
    }
    if (d = JpegMeta.parseNum(c, this._binary_data, b + 2, 2), 42 !== d) throw new Error("Malformed TIFF meta-data. Bad magic: " + d);
    if (e = JpegMeta.parseNum(c, this._binary_data, b + 4, 4), this._parseIfd(c, this._binary_data, b, e, this._tifftags, "tiff", "TIFF"), this.tiff.ExifIfdPointer && this._parseIfd(c, this._binary_data, b, this.tiff.ExifIfdPointer.value, this._exiftags, "exif", "Exif"), this.tiff.GPSInfoIfdPointer) {
        if (this._parseIfd(c, this._binary_data, b, this.tiff.GPSInfoIfdPointer.value, this._gpstags, "gps", "GPS"), this.gps.GPSLatitude) {
            var g;
            g = this.gps.GPSLatitude.value[0].asFloat() + 1 / 60 * this.gps.GPSLatitude.value[1].asFloat() + 1 / 3600 * this.gps.GPSLatitude.value[2].asFloat(),
            "S" === this.gps.GPSLatitudeRef.value && (g = -g),
            this.gps._addProperty("latitude", "Dec. Latitude", g)
        }
        if (this.gps.GPSLongitude) {
            var h;
            h = this.gps.GPSLongitude.value[0].asFloat() + 1 / 60 * this.gps.GPSLongitude.value[1].asFloat() + 1 / 3600 * this.gps.GPSLongitude.value[2].asFloat(),
            "W" === this.gps.GPSLongitudeRef.value && (h = -h),
            this.gps._addProperty("longitude", "Dec. Longitude", h)
        }
    }
},
function(a, b) {
    a.Upload = b(a.$, a.DB, a.Tip)
} (this,
function() {
    function a() {}
    function b(a, b) {
        var c = a.base64.split(",")[1],
        d = new XMLHttpRequest,
        e = new FormData,
        f = b.callbacks,
        g = b.param || {
            type: 1
        };
        Q.monitor(window.Worker ? 419377 : 419378);
        for (var h in g) g.hasOwnProperty(h) && e.append("type", g[h]);
        e.append("base64_code", c),
        f.error && d.addEventListener("error", f.error),
        f.abort && d.addEventListener("abort", f.abort),
        f.complete && d.addEventListener("load",
        function(b) {
            var c = JSON.parse(b.target.responseText);
            f.complete(c, a)
        }),
        d.open("POST", "http://upload.buluo.qq.com/cgi-bin/bar/upload/base64image"),
        d.withCredentials = !0,
        d.send(e)
    }
    function c(a, b, c, d) {
        var e = a.getContext("2d");
        switch (d) {
        case 2:
            e.translate(b, 0),
            e.scale( - 1, 1);
            break;
        case 3:
            e.translate(b, c),
            e.rotate(Math.PI);
            break;
        case 4:
            e.translate(0, c),
            e.scale(1, -1);
            break;
        case 5:
            e.rotate(.5 * Math.PI),
            e.scale(1, -1);
            break;
        case 6:
            e.rotate(.5 * Math.PI),
            e.translate(0, -c);
            break;
        case 7:
            e.rotate(.5 * Math.PI),
            e.translate(b, -c),
            e.scale( - 1, 1);
            break;
        case 8:
            e.rotate( - .5 * Math.PI),
            e.translate( - b, 0)
        }
    }
    function d(a) {
        var b = a.naturalWidth,
        c = a.naturalHeight,
        d = !1;
        if (b * c > 1048576) {
            var e = document.createElement("canvas"),
            f = e.getContext("2d");
            e.width = e.height = 1,
            f.drawImage(a, 1 - b, 0),
            d = 0 === f.getImageData(0, 0, 1, 1).data[3],
            console.log("is ios sub sample: ", f.getImageData(0, 0, 1, 1).data, d),
            e = f = null
        }
        return d
    }
    function e(a, b, c) {
        var d, e = document.createElement("canvas"),
        f = e.getContext("2d"),
        g = 0,
        h = c,
        i = c;
        for (e.width = 1, e.height = c, f.drawImage(a, 1 - Math.ceil(Math.random() * b), 0), d = f.getImageData(0, 0, 1, c).data; i > g;) {
            var j = d[4 * (i - 1) + 3];
            0 === j ? h = i: g = i,
            i = h + g >> 1
        }
        return console.log("ratio is ", i / c),
        i / c
    }
    function f(a, b, c, d) {
        d = d || {},
        "square" === d.type ? (a.width = d.s, a.height = d.s) : d.orient >= 5 && d.orient <= 8 ? (a.width = c, a.height = b) : (a.width = b, a.height = c)
    }
    function g(a, b) {
        b = $.extend({
            maxW: 800,
            maxH: 800,
            quality: .92,
            orient: 1
        },
        b);
        var g, h, i, j = b.maxW,
        k = b.maxH,
        l = b.quality,
        m = a.naturalWidth,
        o = a.naturalHeight,
        p = b.type,
        q = b.side,
        r = 0,
        s = 0;
        n() && d(a) && (m /= 2, o /= 2),
        "square" === p ? m > q && m > o ? (g = m * q / o, h = q, r = (m - o) / 2, i = q) : o > q && o > m ? (g = q, h = o * q / m, s = (o - m) / 2, i = q) : (g = m, h = o, i = Math.min(g, h)) : m > j && m / o >= j / k ? (g = j, h = o * j / m) : o > k && o / m >= k / j ? (g = m * k / o, h = k) : (g = m, h = o);
        var t, u = document.createElement("canvas"),
        v = u.getContext("2d");
        if (f(u, g, h, $.extend({
            s: i
        },
        b)), c(u, g, h, b.orient), n()) {
            var w, x, y, z, A, B, C, D, E = document.createElement("canvas"),
            F = E.getContext("2d"),
            G = 1024,
            H = e(a, m, o);
            for (E.width = E.height = G, w = 0; o > w;) {
                for (z = w + G > o ? o - w: G, x = 0; m > x;) y = x + G > m ? m - x: G,
                F.clearRect(0, 0, G, G),
                F.drawImage(a, -x - r, -w - s),
                A = Math.floor(x * g / m),
                C = Math.ceil(y * g / m),
                B = Math.floor(w * h / o / H),
                D = Math.ceil(z * h / o / H),
                v.drawImage(E, 0, 0, y, z, A, B, C, D),
                x += G;
                w += G
            }
            E = F = null
        } else v.drawImage(a, 0, 0, m, o, 0, 0, g, h);
        if (n()) t = u.toDataURL("image/jpeg", l);
        else {
            var I = v.getImageData(0, 0, u.width, u.height),
            J = new JPEGEncoder(100 * l);
            t = J.encode(I),
            J = null
        }
        return u = v = null,
        t
    }
    function h(a, b) {
        var c = new Image,
        d = a.isNative || !1;
        c.onload = function() {
            b(0, c, {
                w: c.width,
                h: c.height
            })
        },
        c.onerror = function() {
            b(1e3),
            k()
        },
        c.src = d ? a.data: webkitURL.createObjectURL(a)
    }
    function i(a, b) {
        var c = new FileReader;
        c.onloadend = function() {
            var c = this.result,
            d = new JpegMeta.JpegFile(atob(c.split(",")[1]), a);
            b(((d.tiff || {}).Orientation || {}).value || 1)
        },
        c.readAsDataURL(a)
    }
    function j(a, b) {
        var c = new FileReader;
        c.onloadend = function() {
            b(this.result)
        },
        c.readAsDataURL(a)
    }
    function k() {
        Tip.show("暂不支持此格式", {
            type: "warning"
        })
    }
    function l() {
        Tip.show("选择的文件不能超过5M", {
            type: "warning"
        })
    }
    function m(c, d) {
        function e(a) {
            b(a, d)
        }
        var f = c.type,
        m = "image/jpeg" === f,
        n = "image/gif" === f,
        o = !f;
        d = d || {},
        d.callbacks = d.callbacks || {};
        var p = c.isNative || !1;
        return p || o || /^image/.test(c.type) ? !p && c.size > 5242880 ? (d.callbacks.error && d.callbacks.error({
            retcode: 12
        }), l(), {
            retcode: 212
        }) : ((d.callbacks.canUpload || a)(), h(c,
        function(b, h, k) {
            return 1e3 === b ? void(d.callbacks.error && d.callbacks.error({
                retcode: 13
            })) : (m || (d.callbacks.compress || a)($.extend({
                base64: h.src
            },
            k)), void setTimeout(function() {
                p ? (k.base64 = g(h, $.extend(k, d)), k.type = "image/other", e(k)) : m ? i(c,
                function(b) {
                    6 === b && (k = {
                        w: k.h,
                        h: k.w
                    }),
                    (d.callbacks.compress || a)($.extend({
                        base64: h.src
                    },
                    k)),
                    k.base64 = g(h, $.extend({
                        orient: b
                    },
                    k, d)),
                    k.type = f,
                    e(k)
                }) : n ? j(c,
                function(a) {
                    k.base64 = a,
                    k.type = f,
                    e(k)
                }) : o ? j(c,
                function(a) {
                    0 === a.split(",")[1].indexOf("R0lG") ? (k.base64 = a, k.type = "image/gif", e(k)) : (k.base64 = g(h, k), k.type = "image/other", e(k))
                }) : (k.base64 = g(h, k), k.type = "image/other", e(k))
            },
            100))
        }), {
            retcode: 0
        }) : (d.callbacks.error && d.callbacks.error({
            retcode: 11
        }), k(), {
            retcode: 211
        })
    }
    function n() {
        return ! 0
    }
    return m
});