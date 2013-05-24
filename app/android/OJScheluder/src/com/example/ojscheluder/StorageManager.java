package com.example.ojscheluder;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;

import org.json.JSONObject;

import android.os.Environment;
import android.util.Log;

public class StorageManager {
	private static final String TAG = "StorageManager.java";

	public static final String DATA_PATH = Environment
			.getExternalStorageDirectory().toString() + "/OJScheduler/";

	public static boolean createDir(String sPath) {
		File dir = new File(sPath);
		if (!dir.exists()) {
			if (!dir.mkdirs()) {
				Log.v(TAG, "ERROR: Creation of directory " + sPath
						+ " on sdcard failed");
				return false;
			} else {
				Log.v(TAG, "dir" + sPath + "already exists");
			}
		}
		return true;
	}

	public static boolean createRootDir() {
		return createDir(DATA_PATH);
	}

	public static boolean writeData(JSONObject jSchedule, String sPath,
			String sFile) {

		if (!createDir(DATA_PATH + sPath)) {
			return false;
		}
		try {
			BufferedWriter br = new BufferedWriter(new FileWriter(DATA_PATH
					+ sPath + "/" + sFile));
			br.write(jSchedule.toString());
			br.close();
			Log.v(TAG, "data written successfully to " + DATA_PATH + sPath
					+ "/" + sFile);
			return true;
		} catch (IOException e) {
			Log.e(TAG, "Was unable to copy" + e.toString());
			return false;
		}
	}

	public static JSONObject getData(String sPath, String sFile) {
		try {
			BufferedReader br = new BufferedReader(new FileReader(DATA_PATH
					+ sPath + "/" + sFile));
			String line = "", data = "";
			while ((line = br.readLine()) != null) {
				data += line;
			}
			br.close();
			return new JSONObject(data);
		} catch (Exception e) {
			e.printStackTrace();
		}
		return null;
	}
}
