package com.mallchain.wallet.fragents;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.text.TextUtils;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.mallchain.wallet.activity.MainActivity;
import com.mallchain.wallet.model.FullWallet;
import com.mallchain.wallet.model.HttpCallback;
import com.mallchain.wallet.utils.NetWorkUtil;
import com.mallchain.wallet.utils.OwerWalletUtils;
import com.mallchain.wallet.utils.UpdateManager;
import com.mallchain.wallet.utils.WalletStorage;

import com.mallchain.wallet.R;

import org.web3j.crypto.Credentials;
import org.web3j.crypto.Keys;

/**
 * Created by Administrator on 2018/8/2.
 */
public class MnemonicFragment extends Fragment {
    private EditText mneminic;
    private EditText mWalletpassword,mConfirmpassword;
    private TextView loadwallet;
    private boolean isRequest;

    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_mneminic,null);
        isRequest = false;
        mWalletpassword = (EditText) rootView.findViewById(R.id.walletpassword);
        mConfirmpassword = (EditText) rootView.findViewById(R.id.confirmpassword);
        mneminic = (EditText) rootView.findViewById(R.id.mneminic);
        loadwallet = (TextView) rootView.findViewById(R.id.loadwallet);
        loadwallet.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                final String mneminicStr = mneminic.getText().toString();
                if (TextUtils.isEmpty(mneminicStr)){
                    Toast.makeText(getActivity(),"助记词内容为空",Toast.LENGTH_LONG).show();
                    return;
                }
                final String pwd = mWalletpassword.getText().toString();
                if (TextUtils.isEmpty(pwd) || pwd.length()<8){
                    Toast.makeText(getActivity(),"密码不可为空或密码不少于8位",Toast.LENGTH_LONG).show();
                    return;
                }
                String confirpwd = mConfirmpassword.getText().toString();
                if (!TextUtils.equals(pwd,confirpwd)){
                    Toast.makeText(getActivity(),"密码不正确",Toast.LENGTH_LONG).show();
                    return;
                }

                if (isRequest){
                    Toast.makeText(getActivity(),"正在导入中。。。",Toast.LENGTH_LONG).show();
                    return;
                }
                isRequest = true;
                UpdateManager.getInstance().showProgress(getActivity());
                new Thread(new Runnable() {
                    @Override
                    public void run() {
                        try {
                            Credentials credentials = OwerWalletUtils.loadBip39Credentials(pwd,mneminicStr);
                            FullWallet fullWallet = new FullWallet();
                            fullWallet.setMnemonic(mneminicStr);
                            fullWallet.setPassword(pwd);
                            fullWallet.setWalletAdress(Keys.toChecksumAddress(credentials.getAddress()));
                            fullWallet.setPassword(credentials.getEcKeyPair().getPrivateKey().toString(16));
                            int index = WalletStorage.getInstance(getActivity().getApplicationContext()).get().indexOf(fullWallet);
                            if (index == -1){
                                WalletStorage.getInstance(getActivity().getApplicationContext()).add(fullWallet);
                            }else{
                                FullWallet fullWallet1 = WalletStorage.getInstance(getActivity().getApplicationContext()).get().get(index);
                                fullWallet1.setMnemonic(mneminicStr);
                                fullWallet1.setPassword(pwd);
                                WalletStorage.getInstance(getActivity().getApplicationContext()).save();
                            }
                            SharedPreferences preferences = getActivity().getSharedPreferences("data",getActivity().MODE_PRIVATE);
                            preferences.edit().putString("address",fullWallet.getWalletAdress()).commit();
                            updateAddr(credentials.getAddress());
                        }catch (final Exception e){
                            isRequest = false;
                            UpdateManager.getInstance().progressIsNull();
                            getActivity().runOnUiThread(new Runnable() {
                                @Override
                                public void run() {
                                    if (e.getMessage().contains("Invalid password provided")){
                                        Toast.makeText(getActivity(),"密码无效",Toast.LENGTH_LONG).show();
                                    }else{
                                        Toast.makeText(getActivity(),"钱包导入失败",Toast.LENGTH_LONG).show();
                                    }

                                }
                            });
                        }

                    }
                }).start();

            }
        });
        return rootView;
    }

    public void setMnemonic(String mnemonic) {
        mneminic.setText(mnemonic);
    }

    private void updateAddr(String addr){
          NetWorkUtil.updateAddr(addr, new HttpCallback() {
            @Override
            public void onFailure(Exception e) {
                UpdateManager.getInstance().progressIsNull();
                isRequest = false;
                startActivity(new Intent(getActivity(),MainActivity.class));
                getActivity().finish();
            }

            @Override
            public void onResponse(String response) {
                isRequest = false;
                UpdateManager.getInstance().progressIsNull();
                startActivity(new Intent(getActivity(),MainActivity.class));
                getActivity().finish();
            }
        });
    }
}
